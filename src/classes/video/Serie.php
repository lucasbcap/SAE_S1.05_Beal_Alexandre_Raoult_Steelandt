<?php

namespace iutnc\netvod\video;

use iutnc\netvod\db\ConnectionFactory;

class Serie
{

    protected array $listEpisode;
    protected string $titre, $genre, $publiqueVise, $descriptif, $sortie, $dateAjout,$img;
    protected int $nmbEpisode, $id;

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
    public function __construct(string $titre, string $img, string $genre, string $publiqueVise, string $descriptif, string $sortie, string $dateAjout,int $id, array $listEpisode = [])
    {
        $this->listEpisode = $listEpisode;
        $this->img = $img;
        $this->titre = $titre;
        $this->genre = $genre;
        $this->publiqueVise = $publiqueVise;
        $this->descriptif = $descriptif;
        $this->sortie = $sortie;
        $this->dateAjout = $dateAjout;
        $this->id = $id;
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
        $req1 = $bdd->prepare("Select * from serie where id=:id");
        $req1->bindParam(":id", $id);
        $req1->execute();
        $d = $req1->fetch();
        $serie = new Serie($d['titre'],$d['img'], $d['genre'], $d['publicVise'], $d['descriptif'], $d['annee'],$d['date_ajout'], $d['id']);

        $req2 = $bdd->prepare("Select episode.id from serie inner join episode on serie.id = episode.serie_id where serie.id=:id");
        $req2->bindParam(":id", $id);
        $req2->execute();
        $req2->fetch();
        while ($d = $req2->fetch()) {
            $ep = Episode::chercherEpisode($d['id']);
            $serie->ajouterEpisode($ep);
        }
        return $serie;
    }


}