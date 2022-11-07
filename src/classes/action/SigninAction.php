<?php

namespace iutnc\netvod\action;

use iutnc\netvod\auth\Auth;

class SigninAction extends Action
{

    public function __construct()
    {
        parent::__construct();
    }

    public function execute(): string
    {
        $res ="";
        if($this->http_method == 'GET') $res = $this->register();
        else if ($this->http_method == 'POST') $res = $this->signin();
        return $res;
    }

    function register(): string
    {
        $res = "
    <form action='?action=sign-in' method='POST'>
        <h1>Connexion</h1>

        <label><b>Nom d'utilisateur</b></label>
        <input type='text' placeholder='Entrer le nom d utilisateur' name='username' required><br>

        <label><b>Mot de passe</b></label>
        <input type='password' placeholder='Entrer le mot de passe' name='password' required><br>

        <input type='submit' id='log' value='LOGIN'>
        ";

        if (isset($_GET['error'])) {
            $res .= "<p style='color:red'>Utilisateur ou mot de passe incorrect</p><br>";
        }

        $res .= "</form>
";
        return $res;

    }

    function signin(): string
    {
        $res = "";
        if (isset($_POST['username']) && isset($_POST['password'])) {
            Auth::authenticate();
            if (unserialize($_SESSION['user']) == null) {
                header("Location: ?action=sign-in&error=1");
            } else {
                $res = "Vous êtes connecte en tant que " . $_POST['username'];
            }
        }
        return $res;
    }

}