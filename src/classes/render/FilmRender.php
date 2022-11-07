<?php

namespace iutnc\netvod\Render;

use iutnc\netvod\vidéo\Film;

class FilmRender extends Render
{
    protected Film $film;

    public function __construct(Film $film){
        $this->film = $film;
    }

    public function render(): string
    {
        return "<div class='track'>".
            "<h1>{$this->film->titre}</h1>".
            "<h2>{$this->film->resume}</h2>".
            "<p><video controls src='{$this->film->source}'></video></p>".
            "</div>";
    }
}