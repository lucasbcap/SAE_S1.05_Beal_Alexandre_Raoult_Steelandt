<?php

namespace iutnc\netvod\video;

use iutnc\netvod\db\ConnectionFactory;

class Episode
{
    protected string $titre,$source,$image,$resume;
    protected int $numero,$duree;

    /**
     * @param string $titre
     * @param string $source
     * @param string $image
     * @param string $resume
     * @param int $numero
     * @param int $duree
     */
    public function __construct(string $titre, string $source, string $image, string $resume, int $numero, int $duree)
    {
        $this->titre = $titre;
        $this->source = $source;
        $this->image = $image;
        $this->resume = $resume;
        $this->numero = $numero;
        $this->duree = $duree;
    }

    public static function chercherEpisode(int $id):Episode{
        $bdd = ConnectionFactory::makeConnection();
        $requete = $bdd->prepare("Select * from episode where id=:id");
        $requete->bindParam(":id",$id);
        $requete->execute();
        $d = $requete->fetch();
        return new Episode($d['titre'],"video/".$d['file'],"Image/beach.jpg",$d['resume'],$d['numero'],$d['duree']);
    }

    public static function chercherEpisodeTitre(string $titre):int {
        $bdd = ConnectionFactory::makeConnection();
        $requete = $bdd->prepare("Select id from episode where titre=?");
        $requete->bindParam(1,$titre);
        $requete->execute();
        $d = $requete->fetch();
        return $d['id'];
    }


    public function __get(string $at):mixed {
        if (property_exists($this, $at)) {
            return $this->$at;
        }else {
            throw new \Exception("$at: invalid property");
        }
    }

}