<?php

namespace iutnc\netvod\action;

use iutnc\netvod\auth\Auth;
use iutnc\netvod\db\ConnectionFactory as ConnectionFactory;

class AddUserAction extends Action
{

    public function __construct()
    {
        parent::__construct();
    }

    public function execute(): string
    {
        $res = "";
        if ($this->http_method == 'GET') {
            if (isset($_GET['valide'])) {
                $res = $this->confirmerInscrit();
            } else if(isset($_GET['token']) && Auth::activate($_GET['token'])) {
                $res = $this->inscrit();
            }else{
                $res = $this->inscription();
            }
        }else if ($this->http_method == 'POST') {
            switch ($this->verifInscription()){
                case "EmailExist":
                    header("Location: ?action=add-user&error=1");
                    break;

                case "MdpWrong":
                    header("Location: ?action=add-user&error=2");
                    break;
                case "NotSameMdp":
                    header("Location: ?action=add-user&error=3");
                    break;
                case "Log":
                    header("Location: ?action=add-user&valide=1");;
                    break;
            }
        }
        return $res;
    }

    function inscrit(): string
    {
        $mail = filter_var($_SESSION['email'], FILTER_SANITIZE_EMAIL);
        $pass = $_SESSION['pass'];
        $pass2 = $_SESSION['pass2'];
        session_destroy();
        Auth::register($mail, $pass, $pass2);
        return "<h2> Vous êtes Inscrit ! </h2>";
    }

    function verifInscription():string{
        $r = "Log";
        $_SESSION['email'] = $_POST['email'];
        $_SESSION['pass'] = $_POST['pass'];
        $_SESSION['pass2'] = $_POST['pass2'];
        if ($_SESSION['pass'] == $_SESSION['pass2']) {
            if (Auth::checkPasswordStrength($_SESSION['pass'], 4)) {
                $bdd = ConnectionFactory::makeConnection();
                $c1 = $bdd->prepare("Select * from user where email=:mail");
                $c1->bindParam(":mail", $email);
                $c1->execute();
                $verif = true;
                while ($d = $c1->fetch()) {
                    $verif = false;
                }
                if ($verif) {

                } else {
                    $r = "EmailExist";
                }
            } else {
                $r = "MdpWrong";
            }
        } else {
            $r = "NotSameMdp";
        }
        return $r;
    }

    function confirmerInscrit():string{
        $res = Auth::generateToken("new");
        return "<a href='?action=add-user&token=".$res."'>Confirmer compte</a>";
    }


    function inscription(): string
    {
        $res = "
<form id='sign' method='post' action='?action=add-user'>
<h1>Inscription</h1>
                    <label><b>Email</b><input type='email' name='email' placeholder='Email'></label>
                    <label><b>Mot de passe</b> <input type='password' name='pass' placeholder='Mot de passe'></label>
                    <label><b>Entrer à nouveau votre mot de passe</b> <input type='password' name='pass2' placeholder='Entrer à nouveau votre mot de passe'></label>
                    <input type='submit' id='log' value='INSCRIPTION'>";
        if (isset($_GET['error'])) {
            switch ($_GET['error']) {
                case 1:
                    $res .= "<p style='color:red'>Vous avez déjà un compte avec cette adresse mail</p><br>";
                    break;

                case 2:
                    $res .= "<p style='color:red'>Votre mot de passe doit faire au moins 5 caractères avec un nombre, une minuscule et une majuscule</p><br>";
                    break;

                case 3:
                    $res .= "<p style='color:red'>Votre mot de passe est different entre les 2 champs</p><br>";
                    break;
            }
        }
        $res .= "</form>";
        return $res;
    }
}