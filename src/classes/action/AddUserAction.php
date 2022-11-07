<?php

namespace iutnc\netvod\Action;

use iutnc\netvod\Auth\Auth;
class AddUserAction extends Action
{
    public function __construct()
    {
        parent::__construct();
    }

    public function execute(): string
    {
        if ($this->http_method == "GET") {
            return "
            <h1>Inscription</h1>
            <form method='post' action='?action=add-user'>
                                <label>Email : <input type='email' name='email' placeholder='email'></label>
                                <label>mot de passe : <input type='password' name='pass' placeholder='mot de passe'></label>
                                <label>Retaper mot de passe : <input type='password' name='passBis' placeholder='Retaper mot de passe'></label>
                                <button type='submit'>Valide</button>
            </form> ";
        }

        else if ($this->http_method == "POST") {
            $r = "<p style='color:red'>Votre mot de passe doit faire au moins 4 caractères avec un nombre et une minuscule ou votre mot de passe est différent</p><br>";
            $mail = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
            $pass = filter_var($_POST['pass'], FILTER_SANITIZE_STRING);
            $passBis = filter_var($_POST['passBis'], FILTER_SANITIZE_STRING);
            if (Auth::register($mail, $pass, $passBis)) {
                $r = "Vous êtes inscrit";
            };
            return $r;
        }
        return "";
    }
}