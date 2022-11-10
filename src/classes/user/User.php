<?php

namespace iutnc\netvod\user;

use iutnc\netvod\db\ConnectionFactory as ConnectionFactory;
use iutnc\netvod\video\Serie;

/**
 * Classe User
 */
class User
{
    protected string $email, $passwrd, $token;

    /**
     * Constructeur
     * @param string $email email de l'user
     * @param string $passwrd mot de passe de l'user
     * @param string $token token associe à l'user
     */
    public function __construct(string $email, string $passwrd, string $token)
    {
        $this->email = $email;
        $this->passwrd = $passwrd;
        $this->token = $token;
    }

    /**
     * Supprime une ligne dans une table de notre base de donnee grace à son ID (ne fonctionne que pour estfini/encours/favori)
     * @param int $id id de la ligne que l'on veut supprimer
     * @param string $table table que l'on veut modifie
     * @return void
     */
    function suppSQL(int $id, string $table)
    {
        $bdd = ConnectionFactory::makeConnection();
        $c1 = $bdd->prepare("DELETE FROM $table WHERE email=:mail AND idSerie=:id;");
        $c1->bindParam(":mail", $this->email);
        $c1->bindParam(":id", $id);
        $c1->execute();
    }

    /**
     * Inserer dans une table un nouvel element grace a son id (ne fonctionne que pour estfini/encours/favori)
     * @param int $id id d'une serie que l'on veut inserer
     * @param string $table table ou l'on veut ajoute
     * @param int $idEpisode Numero de l'episode si la table selectionne est 'encours'
     * @return void
     */
    function addSQL(int $id, string $table, int $idEpisode = 0): void
    {
        $bdd = ConnectionFactory::makeConnection();

        $query = "Select * from $table where email=:mail and idSerie=:id";
        $insert = "insert into $table values (:email,:id";
        //La requete est un peu différente si la table est en cours,
        if ($table === "encours") {
            $query .= " and idEpisode=:idEpisode";
            $insert .= ",:idepisode";
        }
        $insert .= ")";

        $c = $bdd->prepare($query);
        $c->bindParam(":mail", $this->email);
        $c->bindParam(":id", $id);
        //Idem ici
        if ($table === "encours") {
            $c->bindParam(":idEpisode", $idEpisode);
        }
        $c->execute();
        $verif = true;
        //On verifie que la donné que l'on veut inserer n'existe pas deja
        while ($d = $c->fetch()) {
            $verif = false;
        }

        //Si elle n'existe pas on l'insert
        if ($verif) {
            $c1 = $bdd->prepare($insert);
            $c1->bindParam(":email", $this->email);
            $c1->bindParam(":id", $id);
            if ($table === "encours") {
                $c1->bindParam(":idepisode", $idEpisode);
            }

            $c1->execute();
        }
    }


    /**
     * Recupère les donnees lie à la serie et à l'user (ne fonctionne que pour estfini/encours/favori)
     * @param string $table table ou l'on veut recupere la serie
     * @return array|null
     */
    function getSQL(string $table): ?array
    {
        $bdd = ConnectionFactory::makeConnection();
        $c1 = $bdd->prepare("select idSerie from $table where email = :email");
        $c1->bindParam(":email", $this->email);
        $c1->execute();
        $array = null;
        while ($d = $c1->fetch()) {
            $serie = Serie::creerSerie($d['idSerie']);
            if ($serie != null) {
                $array[] = $serie;
            }
        }
        return $array;
    }

    /**
     * Trie nos resultats SLQ sur la table serie en fonction de l'attribut sur lequel on veut trier
     * @param string|null $nomAttribut attribut que l'on veut ordonner
     * @return array liste des series dans l'ordre choisis
     */
    static function TrieSQL(string $nomAttribut = null)
    {
        $bdd = ConnectionFactory::makeConnection();
        if ($nomAttribut != null) $query = "select id from serie order by $nomAttribut";
        else $query = "select id from serie";


        $c1 = $bdd->prepare($query);
        $c1->execute();
        $array = null;
        while ($d = $c1->fetch()) {
            $serie = Serie::creerSerie($d["id"]);
            if ($serie != null) {
                $array[] = $serie;
            }
        }
        return $array;
    }


    /**
     * Getter Magique
     *
     * @param string $at
     * @return mixed
     * @throws \Exception
     */
    public function __get(string $at): mixed
    {
        if (property_exists($this, $at)) {
            return $this->$at;
        } else {
            throw new \Exception("$at: invalid property");
        }
    }
}