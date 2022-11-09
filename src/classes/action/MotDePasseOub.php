<?php

namespace iutnc\netvod\action;


class MotDePasseOub extends \iutnc\netvod\action\Action
{

    public function execute(): string
    {
        $res ="";
        if($this->http_method == 'GET') $res = $this->Email();
        else if ($this->http_method == 'POST') $res = $this->verifToken();
        return $res;
    }

    function Email(): string
    {
        $res = "
    <form id='mdpOublie' action='?action=mdpoub' method='POST'>
        <h1>Connexion</h1>

        <label><b>Email</b></label>
        <input type='email' placeholder='Entrer votre mail' name='mail' required><br>
        <input type='submit' id='log' value='LOGIN'>
        ";
        $res .= "</form>
";
        return $res;

    }

    function InsertToken(): string
    {
        $res = "
    <form id='Token' action='?action=mdpoub' method='POST'>
        <h1>Connexion</h1>

        <label><b>Email</b></label>
        <input type='email' placeholder='Entrer votre mail' name='mail' required><br>

        <a href='?action=mdpoub' id='mdpoublier'>Mot de passe oublié</a>
        <input type='submit' id='log' value='LOGIN'>
        ";
        $res .= "</form>
";
        return $res;

    }
    function NewMdp(): string
    {
        $res = "
    <form id='Token' action='?action=mdpoub' method='POST'>
        <h1>Connexion</h1>

        <label><b>Email</b></label>
        <input type='email' placeholder='Entrer votre mail' name='mail' required><br>

        <a href='?action=mdpoub' id='mdpoublier'>Mot de passe oublié</a>
        <input type='submit' id='log' value='LOGIN'>
        ";
        $res .= "</form>
";
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

}
}