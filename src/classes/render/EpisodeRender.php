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
            $html =
                "<h1>Titre : {$this->ep->titre}</h1>" .
                "<div class = 'resume'>Résumé : {$this->ep->resume}</div>  <div class='duree'> Durée : {$this->ep->duree} min </div><br>".
                "</div>";
            $html .=  "<div class='track'>" .
                "<p><video controls src='{$this->ep->source}' type='video/mp4'></video></p>";
        }
        if($selector===2){
            $html = "<a href='?action=display-episode&id=" . Episode::chercherEpisodeTitre($this->ep->titre) . "'>{$this->ep->titre}    | Durée : {$this->ep->duree} min</a>";
        }

        return $html;
    }
}