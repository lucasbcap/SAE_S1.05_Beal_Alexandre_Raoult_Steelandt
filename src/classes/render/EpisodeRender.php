<?php

namespace iutnc\netvod\render;

use iutnc\netvod\video\episode;

/**
 * Class Render pour les episodes
 */

class EpisodeRender extends Render
{

    /**
     * Variable episode
     */
    protected Episode $ep;

    /**
     * Constructeur classique
     * @param Episode $ep
     */
    public function __construct(Episode $ep){
        $this->ep = $ep;
    }

    /**
     * Fonction render pour afficher un episode
     * @param int $selector type d affichage : 1 video / 2 image
     * @return string ce qu on doit afficher
     */
    public function render(int $selector=1): string
    {
        $html = "";
        // le $selector 1 permet d afficher une video donc quand on clique sur un episode on a bine la video
        // qui est afficher
        if($selector===1) {
            $html =
                "<h1>Titre : {$this->ep->titre}</h1>" .
                "<div class = 'resume'>Résumé : {$this->ep->resume}</div>  <div class='duree'> Durée : {$this->ep->duree} min </div><br>".
                "</div>";
            $html .=  "<div class='track'>" .
                "<p><video controls src='{$this->ep->source}' type='video/mp4'></video></p>";
        }

        // le $selector 2 permet d afficher un episode sous forme de liste donc quand on affiche une serie
        if($selector===2){
            $html = "<a href='?action=display-episode&id=" . Episode::chercherEpisodeTitre($this->ep->titre) . "' id='lien'>{$this->ep->titre}    | Durée : {$this->ep->duree} min
                     <div class=zoom>
                    <div class=image>
                                <img id='test' src='Image/" . $this->ep->image . "' width='600' height='380'>

                    </div>
                    </div>
                    </a>
                     ";
        }


        return $html;
    }
}