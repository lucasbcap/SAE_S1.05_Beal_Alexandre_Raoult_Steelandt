<?php

namespace iutnc\netvod\action;


use iutnc\netvod\auth\Auth;

class MotDePasseOubAction extends \iutnc\netvod\action\Action
{

    public function execute(): string
    {
        $res = "";
        if ($this->http_method == 'GET') {
            if (isset($_GET['token']) && Auth::activate($_GET['token'])) {
                $res = $this->NewMdp();
            } else {
                $res = $this->Email();
            }
        } else if ($this->http_method == 'POST')
            if (isset($_SESSION['mail'])) {
                if (isset($_GET['token']) && Auth::activate($_GET['token'])) {
                    if ($_POST['mdp'] != $_POST['verifmdp']) {
                        header("Location : ?action=mdpoub&token=" . $_GET['token'] . "&error=3");
                    }
                    $res = Auth::changerMDP($_SESSION['mail'], $_POST['mdp']);
                    if ($res == "") {
                        header("Location : ?action=mdpoub&token=" . $_GET['token'] . "&error=2");
                    }
                }
            } else {
                $res = $this->envoieToken($_POST['mail']);
                $_SESSION['mail'] = $_POST['mail'];
            }
        return $res;
    }

    function Email(): string
    {
        session_destroy();
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
        if (isset($_GET['token'])) {
            $res .= "<p style='color:red'>Mauvais Token</p><br>";
        }

        $res .= "</form>";
        return $res;
    }

    public function envoieToken(string $email): string
    {
        $res = Auth::generateToken($email);
        if ($res == "") {
            header("Location: ?action=mdpoub&error=1");
        } else {
            $res = "<a href='?action=mdpoub&token=$res'>Lien de reset mot de passe</a>";
        }
        return $res;
    }

    function NewMdp(): string
    {
        $res = "
    <form id='sign' action='?action=mdpoub&token=".$_GET['token']."' method='POST'>
        <h1>Changement</h1>

        <label><b>Nouveau mot de passe :</b></label>
        <input type='password' placeholder='Mot de passe' name='mdp' required><br>
        <input type='password' placeholder='Entre a nouveau votre mot de passe' name='verifmdp' required><br>
        <input type='submit' id='log' value='Changer'>
        ";
        if (isset($_GET['error'])) {
            switch ($_GET['error']) {
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