<?php

namespace iutnc\netvod\action;


use iutnc\netvod\auth\Auth;
use iutnc\netvod\db\ConnectionFactory;

class MotDePasseOubAction extends \iutnc\netvod\action\Action
{

    protected string $token;
    protected string $email;

    public function execute(): string
    {
        $res = "";
        if ($this->http_method == 'GET') {
            if (isset($_GET['token']) && Auth::activate($_GET['token'])) {
                $res = $this->NewMdp();
            } else {
                $res = $this->Email();
            }
        }
        else if ($this->http_method == 'POST')
            if (isset($_SESSION['mail'])) {
                $res = Auth::changerMDP($_SESSION['mail'],$_POST['mdp']);
            }else{
                $res = $this->envoieToken($_POST['mail']);
            }
        return $res;
    }

    function Email(): string
    {
        $res = "
        <form id='sign' action='?action=mdpoub' method='POST'>
        <h1>Mot de passe oublie</h1>
        <label><b>Email</b></label>
        <input type='email' placeholder='Entrer votre mail' name='mail' required><br>
        <input type='submit' id='log' value='Envoyer un mail'>
        ";

        if (isset($_GET['error'])) {
            $res .= "<p style='color:red'>Cette email n'existe pas</p><br>";
        }

        $res .= "</form>";
        return $res;
    }

    public function envoieToken(string $email):string{
        $res = Auth::generateToken($email);
        if ($res=="") {
            header("Location: ?action=mdpoub&error=1");
        }else{
            $res="<a href='?action=mdpoub&token=".Auth::$token."'>Lien de reset mot de passe</a>";
            $_SESSION['mail'] = $_POST['mail'];
        }
        return $res;
    }

    function NewMdp(): string
    {
        $res = "
    <form id='sign' action='?action=mdpoub' method='POST'>
        <h1>Changement</h1>

        <label><b>Nouveau mot de passe :</b></label>
        <input type='password' placeholder='Mot de passe' name='mdp' required><br>
        <input type='password' placeholder='Entre a nouveau votre mot de passe' name='verifmdp' required><br>
        <input type='submit' id='log' value='Changer'>
        ";
        $res .= "</form>";
        return $res;

    }
}