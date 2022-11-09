<?php

namespace iutnc\netvod\action;


class MotDePasseOubAction extends \iutnc\netvod\action\Action
{

    protected int $token;

    public function execute(): string
    {
        $res = "";
        if ($this->http_method == 'GET')
            if (isset($_GET['token'])) {
                if ($_GET['token']==$this->token)
                $res = $this->InsertToken();
            }else{
                $res = $this->Email();
            }
        else if ($this->http_method == 'POST') $res = $this->verifToken();
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
        $res .= "</form>";
        return $res;

    }

    function NewMdp(): string
    {
        $res = "
    <form id='sign' action='?action=mdpoub' method='POST'>
        <h1>Connexion</h1>

        <label><b>Email</b></label>
        <input type='password' placeholder='Entrer votre mail' name='mdp' required><br>
        <input type='password' placeholder='Entrer votre mail' name='verifmdp' required><br>
        <input type='submit' id='log' value='Changer'>
        ";
        $res .= "</form>";
        return $res;

    }


    function verifToken(): string
    {
        $res = "";
        if (isset($_POST['mail']) && isset($_POST['password'])) {
            Auth::authenticate();
            if (unserialize($_SESSION['user']) == null) {
                header("Location: ?action=sign-in&error=1");
            } else {
                $res = "Vous êtes connecte en tant que " . $_POST['mail'];
            }
        }
        return $res;
    }

    function insertMdp(): string
    {
        $res = "";
        if (isset($_POST['mail']) && isset($_POST['password'])) {
            Auth::authenticate();
            if (unserialize($_SESSION['user']) == null) {
                header("Location: ?action=sign-in&error=1");
            } else {
                $res = "Vous êtes connecte en tant que " . $_POST['mail'];
            }
        }
        return $res;
    }

}