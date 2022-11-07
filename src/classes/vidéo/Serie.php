<?php

namespace iutnc\netvod\vidéo;

class Serie
{

    protected array $episode;
    protected int $nbsaison;
    protected string $genre,$type;

    /**
     * @param int $nbsaison
     * @param string $genre
     * @param string $type
     */
    public function __construct(int $nbsaison, string $genre, string $type)
    {
        $this->episode = [];
        $this->nbsaison = $nbsaison;
        $this->genre = $genre;
        $this->type = $type;
    }

    public function ajouterSerie(array $d){
        array_push($this->episode,$d);
    }



}