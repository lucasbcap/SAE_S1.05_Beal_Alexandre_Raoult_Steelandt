<?php

namespace iutnc\netvod\video;

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
    public function __construct(array $listEpisode = [], string $titre, string $genre, string $publiqueVise, string $descriptif, string $sortie)
    {
        $this->listEpisode = $listEpisode;
        $this->titre = $titre;
        $this->genre = $genre;
        $this->publiqueVise = $publiqueVise;
        $this->descriptif = $descriptif;
        $this->sortie = $sortie;
        $this->dateAjout = date('y-m-j');
        $this->nmbEpisode = count($listEpisode);
    }


    public function ajouterEpisode(Episode $d): void
    {
        array_push($this->saison, $d);
    }


}