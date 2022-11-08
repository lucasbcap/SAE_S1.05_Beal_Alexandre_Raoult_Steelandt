<?php

namespace iutnc\netvod\user;

use iutnc\netvod\db\ConnectionFactory as ConnectionFactory;
use iutnc\netvod\video\Serie;

class User
{
    protected string $email, $passwrd;

    /**
     * @param string $email
     * @param string $passwrd
     */
    public function __construct(string $email, string $passwrd)
    {
        $this->email = $email;
        $this->passwrd = $passwrd;
    }

    function addSQL(int $id, string $table):void{
        $bdd = ConnectionFactory::makeConnection();
        $c1 = $bdd->prepare("insert into $table values (:email,:id)");
        $c1->bindParam(":email",$this->email);
        $c1->bindParam(":email",$id);
        $c1->execute();
    }


    function getFavorie(): ?array
    {
        $bdd = ConnectionFactory::makeConnection();
        $c1 = $bdd->prepare("select idSerie from favori where email = :email");
        $c1->bindParam(":email",$this->email);
        $c1->execute();
        $array = null;
        while ($d = $c1->fetch()) {
            $serie = Serie::creerSerie($d['idSerie']);
            if ($serie!=null) {
                $array[] = $serie;
            }
        }
        return $array;
    }

    /**
     * @return string email
     */
    public function getEmail(): string
    {
        return $this->email;
    }
}