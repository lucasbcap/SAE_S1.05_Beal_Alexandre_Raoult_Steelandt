<?php

namespace iutnc\netvod\video;

use iutnc\netvod\db\ConnectionFactory;

class Serie
{

    protected array $listEpisode;
    protected string $titre, $genre, $publiqueVise, $descriptif, $sortie, $dateAjout;
    protected int $nmbEpisode;

    /**
     * @param array $listEpisode
     * @param string $titre
     * @param string $genre
     * @param string $publiqueVise
     * @param string $descriptif
     * @param string $sortie
     * @param string $dateAjout
     * @param int $nmbEpisode
     */
    public function __construct(string $titre, string $genre, string $publiqueVise, string $descriptif, string $sortie, string $dateAjout, array $listEpisode = [])
    {
        $this->listEpisode = $listEpisode;
        $this->titre = $titre;
        $this->genre = $genre;
        $this->publiqueVise = $publiqueVise;
        $this->descriptif = $descriptif;
        $this->sortie = $sortie;
        $this->dateAjout = $dateAjout;
        $this->nmbEpisode = count($listEpisode);
    }

    public function ajouterEpisode(Episode $d): void
    {
        $this->nmbEpisode++;
        $this->listEpisode[$this->nmbEpisode] = $d;
    }

    public function __get(string $at): mixed
    {
        if (property_exists($this, $at)) {
            return $this->$at;
        } else {
            throw new \Exception("$at: invalid property");
        }
    }

    public static function creerSerie(int $id): Serie
    {
        $bdd = ConnectionFactory::makeConnection();
        $requete = $bdd->prepare("Select serie.titre,descriptif,annee,date_ajout,episode.id from serie inner join episode on serie.id = episode.serie_id where serie.id=:id");
        $requete->bindParam(":id", $id);
        $requete->execute();
        $serie = null;
        $creation = false;
        while ($d = $requete->fetch()) {
            if (!$creation) {
                $serie = new Serie($d['titre'], "null", "null", $d['descriptif'], "ok", $d['descriptif']);
                $creation = true;
            }
            $ep = Episode::chercherEpisode($d['id']);
            $serie->ajouterEpisode($ep);
        }
        return $serie;
    }


}