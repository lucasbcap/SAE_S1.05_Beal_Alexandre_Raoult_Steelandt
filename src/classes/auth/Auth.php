<?php

namespace iutnc\netvod\auth;

use iutnc\netvod\User as User;
use iutnc\netvod\db\ConnectionFactory as ConnectionFactory;

class Auth
{

    public static function authenticate()
    {
        $username = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
        $pass = filter_var($_POST['password'], FILTER_SANITIZE_STRING);
        $bdd = ConnectionFactory::makeConnection();
        $c1 = $bdd->prepare("Select passwd, role from user where email=:mail");
        $c1->bindParam(":mail", $username);
        $_SESSION['mdp'] = $pass;
        $c1->execute();
        $mdpbdd = "";
        $role=1;
        while ($d = $c1->fetch()) {
            $mdpbdd = $d['passwd'];
            $role = $d['role'];
        }
        if (password_verify($pass, $mdpbdd)) {
            $_SESSION['user'] = serialize(new User($username, $mdpbdd, $role));
        }else {
            $_SESSION['user'] = null;
        }
    }

    public static function register(string $email, string $pass, string $pass2):string{
        $r = "Log";
        if ($pass==$pass2) {
            if (self::checkPasswordStrength($pass,4)) {
                $bdd = ConnectionFactory::makeConnection();
                $c = $bdd->prepare("Select * from user where email=:mail");
                $c->bindParam(":mail", $email);
                $c->execute();
                $verif = true;
                while ($d = $c->fetch()) {
                    $verif = false;
                }
                if ($verif) {
                    $c1 = $bdd->prepare("insert into user (email, passwd) values(:email,:pass);");
                    $c1->bindParam(":email", $email,);
                    $pass = password_hash($pass, PASSWORD_DEFAULT, ['cost' => 12]);
                    $c1->bindParam(":pass", $pass);
                    $c1->execute();
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

    public static function verifPl(int $id):bool
    {
        $res = false;
        if (isset($_SESSION['user']) && unserialize($_SESSION['user']) != null) {
            $user = unserialize($_SESSION['user']);
            if ($user->getRole() == 100){
                $res=true;
            }else {
                $bdd = ConnectionFactory::makeConnection();
                $user = unserialize($_SESSION['user']);
                $c1 = $bdd->prepare("Select id_pl from user 
            inner join user2playlist on user.id=user2playlist.id_user 
                where email=:mail;");
                $mail = $user->getEmail();
                $c1->bindParam(':mail',$mail);
                $c1->execute();
                while($d = $c1->fetch()){
                    if ($d['id_pl'] == $id){
                        $res=true;
                    }
                }
            }

        }
        return $res;
    }

}