<?php

namespace iutnc\netvod\video;

use iutnc\netvod\db\ConnectionFactory;

/**
 * class Episode
 */
class Episode
{
    /**
     * toutes les variables d episode
     */
    protected string $titre,$source,$image,$resume;
    protected int $numero,$duree;

    /**
     * constructeur classique
     * @param string $titre
     * @param string $source
     * @param string $image
     * @param string $resume
     * @param int $numero
     * @param int $duree
     * @param int $serie
     */
    public function __construct(string $titre, string $source, string $image, string $resume, int $numero, int $duree,int $serie)
    {
        $this->titre = $titre;
        $this->source = $source;
        $this->image = $image;
        $this->resume = $resume;
        $this->numero = $numero;
        $this->duree = $duree;
        $this->serie = $serie;
    }

    /**
     * methode qui permet de creer un episode suivant son id
     * @param int $id
     * @return Episode
     */
    public static function chercherEpisode(int $id):Episode{
        $bdd = ConnectionFactory::makeConnection();
        $requete = $bdd->prepare("Select * from episode where id=:id");
        $requete->bindParam(":id",$id);
        $requete->execute();
        $d = $requete->fetch();
        return new Episode($d['titre'],"video/".$d['file'],$d['image'],$d['resume'],$d['numero'],$d['duree'],$d['serie_id']);
    }

    /**
     * methode qui permet de cherche l id de l episode suivant son titre
     * @param string $titre
     * @return int id episode
     */
    public static function chercherEpisodeTitre(string $titre):int {
        $bdd = ConnectionFactory::makeConnection();
        $requete = $bdd->prepare("Select id from episode where titre=?");
        $requete->bindParam(1,$titre);
        $requete->execute();
        $d = $requete->fetch();
        return $d['id'];
    }

    /**
     * methode qui cherche l id d un episode suivant l id de la serie et sont numero dans la serie
     * @param int $numero
     * @param int $idSerie
     * @return int
     */
    public static function chercherEpisodeNumero(int $numero,int $idSerie):int {
        $bdd = ConnectionFactory::makeConnection();
        $requete = $bdd->prepare("Select id from episode where numero=? and serie_id=?");
        $requete->bindParam(1,$numero);
        $requete->bindParam(2,$idSerie);
        $requete->execute();
        $d = $requete->fetch();
        return $d['id'];
    }

    /**
     * methode qui cherche l id de la serie pour un episode
     * @param int $idEpisode
     * @return int id serie
     */
    public static function chercherSerie(int $idEpisode):int {
        $bdd = ConnectionFactory::makeConnection();
        $requete = $bdd->prepare("Select serie_id from episode where id=?");
        $requete->bindParam(1,$idEpisode);
        $requete->execute();
        $d = $requete->fetch();
        return $d['serie_id'];
    }


    /**
     * Getter magique
     * @param string $at
     * @return mixed
     * @throws \Exception
     */
    public function __get(string $at):mixed {
        if (property_exists($this, $at)) {
            return $this->$at;
        }else {
            throw new \Exception("$at: invalid property");
        }
    }

}