<?php

namespace iutnc\netvod\video;

class Serie
{

    protected array $saison;
    protected int $nbsaison;
    protected string $genre,$type;

    /**
     * @param int $nbsaison
     * @param string $genre
     * @param string $type
     */
    public function __construct(int $nbsaison, string $genre, string $type)
    {
        $this->saison = [];
        $this->nbsaison = $nbsaison;
        $this->genre = $genre;
        $this->type = $type;
    }

    public function ajouterSaison(Saison $d):void{
        array_push($this->saison,$d);
    }



}