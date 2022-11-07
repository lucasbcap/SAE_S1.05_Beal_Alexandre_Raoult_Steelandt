<?php

namespace iutnc\netvod\render;

use iutnc\netvod\video\episode;

class EpisodeRender extends Render
{
    protected Episode $ep;

    public function __construct(Episode $ep){
        $this->ep = $ep;
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