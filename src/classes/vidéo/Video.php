<?php

namespace iutnc\netvod\vidéo;

abstract class Video
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


    public function __get(string $at):mixed {
        if (property_exists($this, $at)) {
            return $this->$at;
        }else {
            throw new \Exception("$at: invalid property");
        }
    }

}