<?php

namespace iutnc\netvod\auth;

use iutnc\netvod\User\User as User;
use iutnc\netvod\db\ConnectionFactory as ConnectionFactory;

class Auth
{

    public static function authenticate(): ?User
    {
        $username = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
        $pass = filter_var($_POST['password'], FILTER_SANITIZE_STRING);
        $bdd = ConnectionFactory::makeConnection();
        $c1 = $bdd->prepare("Select passwd from user where email=:mail");
        $c1->bindParam(":mail", $username);
        $_SESSION['mdp'] = $pass;
        $c1->execute();
        $mdpbdd = "";
        while ($d = $c1->fetch()) {
            $mdpbdd = $d['passwd'];
        }
        if (password_verify($pass, $mdpbdd)) {
            return new User($username, $mdpbdd, 1);
        }
        return null;
    }

    public static function register(string $email, string $pass):bool{
        $r=false;
        if (self::checkPasswordStrength($pass)){
            if(!self::verifUser($email)) {
                $bdd = ConnectionFactory::makeConnection();

                $c = $bdd->prepare("select count(*)+1 as compte from user");
                $c->execute();
                $compte = 0;
                while ($d = $c->fetch()) {
                    $compte = $d['compte'];
                }

                $c1 = $bdd->prepare("insert into user (id,email, passwd) values(:id,:email,:pass);");
                $c1->bindParam(":id", $compte);
                $c1->bindParam(":email", $email);
                $pass = password_hash($pass, PASSWORD_DEFAULT, ['cost' => 12]);
                $c1->bindParam(":pass", $pass);
                $c1->execute();
                $r = true;
            }
        }
        return $r;
    }

    public static function verifUser(string $mailUser):bool{
        $bdd = ConnectionFactory::makeConnection();
        $r=false;
        $c3 = $bdd->prepare("select count(*) as compte from user 
                            where email=?");
        $c3->bindParam(1,$mailUser);
        $c3->execute();
        $compte = 0;
        while ($d = $c3->fetch()) {
            $compte = $d['compte'];
        }

        if($compte !=0 || $mailUser==="admin@mail.com") $r = true;
        return $r;
    }


    public  static function checkPasswordStrength(string $pass): bool {

        $length = (strlen($pass) < 4); // longueur minimale
        $digit = preg_match("#[\d]#", $pass); // au moins un digit
        $lower = preg_match("#[a-z]#", $pass); // au moins une minuscule
        if ($length || !$digit || !$lower)return false;
        return true;
    }

}