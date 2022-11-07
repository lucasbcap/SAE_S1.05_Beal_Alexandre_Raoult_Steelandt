<?php

namespace iutnc\netvod\video;

class Episode
{
    protected string $titre,$source,$image,$resume,$genre,$publiqueVise;
    protected int $numero,$duree;

    /**
     * @param string $titre
     * @param string $source
     * @param string $image
     * @param string $resume
     * @param int $numero
     * @param int $duree
     */
    public function __construct(string $titre, string $source, string $image, string $resume, int $numero, int $duree,string $genre,$publiqueVise)
    {
        $this->titre = $titre;
        $this->source = $source;
        $this->image = $image;
        $this->resume = $resume;
        $this->numero = $numero;
        $this->duree = $duree;
        $this->genre=$genre;
        $this->publiqueVise=$publiqueVise;
    }


    public function __get(string $at):mixed {
        if (property_exists($this, $at)) {
            return $this->$at;
        }else {
            throw new \Exception("$at: invalid property");
        }
    }

}