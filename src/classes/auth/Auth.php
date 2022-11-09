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
        $c1 = $bdd->prepare("Select passwd from user where email=:mail");
        $c1->bindParam(":mail", $username);
        $_SESSION['mdp'] = $pass;
        $c1->execute();
        $mdpbdd = "";
        $role=1;
        while ($d = $c1->fetch()) {
            $mdpbdd = $d['passwd'];
        }
        if (password_verify($pass, $mdpbdd)) {
            $_SESSION['user'] = serialize(new User($username, $mdpbdd));
        }else {
            $_SESSION['user'] = null;
        }
    }

    public static function register(string $email, string $pass, string $pass2):string{
        $r = "Log";
        if ($pass==$pass2) {
            if (self::checkPasswordStrength($pass,4)) {
                $bdd = ConnectionFactory::makeConnection();
                $c1 = $bdd->prepare("Select * from user where email=:mail");
                $c1->bindParam(":mail", $email);
                $c1->execute();
                $verif = true;
                while ($d = $c1->fetch()) {
                    $verif = false;
                }
                if ($verif) {
                    $c2 = $bdd->prepare("insert into user values(:email,:pass,null,null,null,1);");
                    $c2->bindParam(":email", $email,);
                    $pass = password_hash($pass, PASSWORD_DEFAULT, ['cost' => 12]);
                    $c2->bindParam(":pass", $pass);
                    $c2->execute();
                } else {
                    $r = "EmailExist";
                }
            } else {
                $r = "MdpWrong";
            }
        }else{
            $r= "NotSameMdp";
        }
        return $r;
    }

    public  static function checkPasswordStrength(string $pass, int $minimumLength): bool {

        $length = (strlen($pass) < $minimumLength); // longueur minimale
        $digit = preg_match("#[\d]#", $pass); // au moins un digit
        $lower = preg_match("#[a-z]#", $pass); // au moins une minuscule
        $upper = preg_match("#[A-Z]#", $pass); // au moins une majuscule
        if ($length || !$digit || !$lower || !$upper)return false;
        return true;

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

    public static function changerMDP(string $mailUser,string $newMDP):string{
        $bdd = ConnectionFactory::makeConnection();
        $r=false;
        $c3 = $bdd->prepare("Update user set passwd =:mdp
                            where email=:email");
        $c3->bindParam(":email",$mailUser);
        $c3->bindParam(":mdp",$newMDP);
        $c3->execute();
        return "<h2>Votre mot de pass a bien été modifié</h2>";
    }

}