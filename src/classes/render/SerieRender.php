<?php

namespace iutnc\netvod\render;

use iutnc\netvod\video\Serie;

/**
 * Class Render pour les series
 */

class SerieRender extends Render
{

    /**
     * Variable serie
     */
    protected ?Serie $list=null;

    /**
     * Constructeur classique
     * @param Serie $list
     */
    public function __construct(Serie $list){
        $this->list = $list;
    }

    /**
     * Fonction render pour afficher une serie
     * @param int $selector type d affichage
     * @return string ce qu on doit afficher
     */
    public function render(int $selector): string
    {

        $html = "
            <div><h1>{$this->list->titre}</h1>".
            "<p>Description : {$this->list->descriptif}</p>".
            "<p>Genre : {$this->list->genre} /  Public visé : {$this->list->publiqueVise}</p>";
        $html.="<p>Nombre d'épisodes : {$this->list->nmbEpisode} / Année de sortie : {$this->list->sortie} </p>";
        $html .="<h3>Listes des épisodes : </h3>";

        // on affiche le detail de la serie
        // puis on affiche chacun de c est episode donc on selectionne les episodes
        // et on appelle le EpisodeRender sur chacun d entre eux

        $i = 1;
        foreach($this->list->listEpisode as $track){
            $episodeRender = new EpisodeRender($track);
            $html .= "<p>" .$i.". ".$episodeRender->render($selector)."</p>";
            $i++;
        }
        $html.="</div>";

        // on retourne le tout
        return $html;
    }
}