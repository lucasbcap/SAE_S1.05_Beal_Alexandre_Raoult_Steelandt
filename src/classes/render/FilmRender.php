<?php

namespace iutnc\netvod\Render;

use iutnc\netvod\vidéo\Film;

class FilmRender extends Render
{
    protected Film $film;

    public function __construct(Film $film){
        $this->film = $film;
    }

    public function render(int $selector=1): string
    {
        $html = "";
        if($selector===1) {
            $html .=  "<div class='track'>" .
                "<p><video controls src='{$this->ep->source}'></video></p>";
        }
        if($selector===2){
            $html .= "<div class='track'>" .
                "<p><img controls src='{$this->ep->image}'></img></p>";
        }
        $html .=
            "<h1>Titre : {$this->ep->titre}</h1>" .
            "<p>Résumé :{$this->ep->resume} Durée :{$this->ep->duree}</p>".
            "</div>";
        return $html;
    }
}