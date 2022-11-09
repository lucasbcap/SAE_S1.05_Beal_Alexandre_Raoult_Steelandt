<?php

namespace iutnc\netvod\action;

use iutnc\netvod\auth\Auth;

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
            } else {
                $res = $this->inscription();
            }
        } else if ($this->http_method == 'POST') $res = $this->inscrit();
        return $res;
    }

    function inscrit(): string
    {
        $mail = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
        $pass = $_POST['pass'];
        $pass2 = $_POST['pass2'];
        $_SESSION['mail'] = $mail;
        $res = Auth::register($mail, $pass, $pass2);
        switch ($res) {
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
                header("Location: ?action=add-user&valide=1");
                break;
        }
        return $res;
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