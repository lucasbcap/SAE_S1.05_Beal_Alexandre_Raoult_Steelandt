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

    function suppSQL(int $id,string $table){
        $bdd = ConnectionFactory::makeConnection();
        $val = "idSerie";

        $c1 = $bdd->prepare("DELETE FROM $table WHERE email=:mail AND $val=:id;");
        $c1->bindParam(":mail", $this->email);
        $c1->bindParam(":id", $id);
        $c1->execute();
    }

    function addSQL(int $id, string $table, int $idEpisode = 0):void{
        $bdd = ConnectionFactory::makeConnection();
        $val = "idSerie";
        $query ="Select * from $table where email=:mail and $val=:id";
        $insert = "insert into $table values (:email,:id)";
        if ($table==="enCours"){
            $query = "Select * from $table where email=:mail and $val=:id and idEpisode=:idEpisode";
            $insert = "insert into $table values (:email,:id,:idepisode)";
        }


        $c = $bdd->prepare($query);
        $c->bindParam(":mail", $this->email);
        $c->bindParam(":id", $id);
        if ($table==="enCours"){
            $c->bindParam(":idEpisode",$idEpisode);
        }
        $c->execute();
        $verif = true;
        while ($d = $c->fetch()) {
            $verif = false;
        }

        if($verif) {
            $c1 = $bdd->prepare($insert);
            $c1->bindParam(":email", $this->email);
            $c1->bindParam(":id", $id);
            if ($table==="enCours"){
                $c1->bindParam(":idepisode",$idEpisode);
            }

            $c1->execute();
        }
    }


    function getSQL(string $table): ?array
    {
        $bdd = ConnectionFactory::makeConnection();
        $val = "idSerie";
        if ($table=="encours"){
            $val = "idVideo";
        }
        $c1 = $bdd->prepare("select $val from $table where email = :email");
        $c1->bindParam(":email",$this->email);
        $c1->execute();
        $array = null;
        while ($d = $c1->fetch()) {
            $serie = Serie::creerSerie($d[$val]);
            if ($serie!=null) {
                $array[] = $serie;
            }
        }
        return $array;
    }

    static function TrieSQL(string $nomAttribut=null){
        $bdd = ConnectionFactory::makeConnection();
        if ($nomAttribut != null)$query = "select id from serie order by $nomAttribut";
        else $query ="select id from serie";


        $c1 = $bdd->prepare($query);
        $c1->execute();
        $array = null;
        while ($d = $c1->fetch()) {
            $serie = Serie::creerSerie($d["id"]);
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