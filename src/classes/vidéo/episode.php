<?php

namespace iutnc\netvod\vidéo;

class episode extends Video
{

    public function __construct(string $titre, string $source, string $image, string $resume, int $numero, int $duree)
    {
        parent::__construct($titre,$source,$image,$resume,$numero,$duree);
    }

}