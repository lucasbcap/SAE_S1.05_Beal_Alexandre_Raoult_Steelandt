<?php

namespace iutnc\netvod\auth;

use iutnc\netvod\user\User;
use iutnc\netvod\db\ConnectionFactory as ConnectionFactory;

class Auth
{
    public static function authenticate()
    {
        $username = filter_var($_POST['mail']);
        $pass = filter_var($_POST['password']);
        $bdd = ConnectionFactory::makeConnection();
        $c1 = $bdd->prepare("Select passwd, token from user where email=:mail");
        $c1->bindParam(":mail", $username);
        $_SESSION['mdp'] = $pass;
        $c1->execute();
        $mdpbdd = "";
        $token = 1;
        while ($d = $c1->fetch()) {
            $mdpbdd = $d['passwd'];
            $token = $d['token'];
        }
        if (password_verify($pass, $mdpbdd)) {
            $_SESSION['user'] = serialize(new User($username, $mdpbdd, $token));
        } else {
            $_SESSION['user'] = null;
        }
    }

    public static function register(string $email, string $pass, string $pass2): string
    {
        $bdd = ConnectionFactory::makeConnection();
        $token = Auth::generateToken($email);
        $c2 = $bdd->prepare("insert into user values(:email,:pass,null,null,null,1,:token);");
        $c2->bindParam(":email", $email,);
        $pass = password_hash($pass, PASSWORD_DEFAULT, ['cost' => 12]);
        $c2->bindParam(":pass", $pass);
        $c2->bindParam(":token", $token);
        $c2->execute();
        $_POST['mail'] = $email;
        $_POST['password'] = $pass;
        Auth::authenticate();
        return "Log";
    }

    public static function checkPasswordStrength(string $pass, int $minimumLength): bool
    {

        $length = (strlen($pass) < $minimumLength); // longueur minimale
        $digit = preg_match("#[\d]#", $pass); // au moins un digit
        $lower = preg_match("#[a-z]#", $pass); // au moins une minuscule
        $upper = preg_match("#[A-Z]#", $pass); // au moins une majuscule
        if ($length || !$digit || !$lower || !$upper) return false;
        return true;

    }

    public static function verifUser(string $mailUser): bool
    {
        $bdd = ConnectionFactory::makeConnection();
        $r = false;
        $c3 = $bdd->prepare("select count(*) as compte from user 
                            where email=?");
        $c3->bindParam(1, $mailUser);
        $c3->execute();
        $compte = 0;
        while ($d = $c3->fetch()) {
            $compte = $d['compte'];
        }

        if ($compte != 0 || $mailUser === "admin@mail.com") $r = true;
        return $r;
    }

    public static function changerMDP(string $mailUser, string $newMDP): string
    {
        $bdd = ConnectionFactory::makeConnection();
        $res = "";
        if (self::checkPasswordStrength($newMDP, 4)) {
            $c3 = $bdd->prepare("Update user set passwd =:mdp
                            where email=:email");
            $pass = password_hash($newMDP, PASSWORD_DEFAULT, ['cost' => 12]);
            $c3->bindParam(":email", $mailUser);
            $c3->bindParam(":mdp", $pass);
            $c3->execute();
            session_destroy();
            $res = "<h2>Votre mot de passe a bien été modifié</h2>";
        }
        return $res;
    }

    public static function generateToken(string $email): string
    {
        $token = "";
        $bdd = ConnectionFactory::makeConnection();
        $req1 = $bdd->prepare("select * from user where email=:email");
        $req1->bindParam(":email", $email);
        $req1->execute();
        $verif = false;
        while ($d = $req1->fetch()) {
            $verif = true;
        }
        $res = "";
        if ($verif || $email=="new") {
            $chaine = "a0b1c2d3e4f5g6h7i8j9klmnpqrstuvwxy123456789";
            for ($i = 0; $i < 50; $i++) {
                $token .= $chaine[rand() % strlen($chaine)];
            }
            $res = $token;
            $req2 = $bdd->prepare("update user set token=:token where email=:email");
            $req2->bindParam(":email", $email);
            $req2->bindParam(":token", $token);
            $req2->execute();
        }
        return $res;
    }

    public static function activate(string $token): bool
    {
        $bdd = ConnectionFactory::makeConnection();
        $req1 = $bdd->prepare("select token from user where email=:email");
        $req1->bindParam(":email", $_SESSION['mail']);
        $req1->execute();
        $res = false;
        while ($d = $req1->fetch()) {
            if ($d['token'] == $token && $token != "") {
                $res = true;
            }
        }
        return true;
    }
}