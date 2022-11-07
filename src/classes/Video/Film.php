<?php

namespace iutnc\netvod\Video;

class Film extends Video
{
    protected string $genre,$typePublique;

    /**
     * @param string $genre
     * @param string $typePublique
     */
    public function __construct(string $titre, string $source, string $image, string $resume, int $numero, int $duree,string $genre, string $typePublique)
    {
        parent::__construct($titre,$source,$image,$resume,$numero,$duree);
        $this->genre = $genre;
        $this->typePublique = $typePublique;
    }




}