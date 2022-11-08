<?php

namespace iutnc\netvod\render;

use iutnc\netvod\video\Episode;
use iutnc\netvod\video\Serie;

class SerieRender extends Render
{

    protected ?Serie $list=null;
    public function __construct(Serie $list){
        $this->list = $list;
    }

    public function render(int $selector): string
    {
        $html = "
            <div><h1>{$this->list->titre}</h1>".
            "<p>Description : {$this->list->descriptif}</p>".
            "<p>Genre : {$this->list->genre} /  Public visé : {$this->list->publiqueVise}</p>";
        $html.="<p>Nombre d'épisodes : {$this->list->nmbEpisode} / Année de sortie : {$this->list->sortie} </p>";
        $html .="<h3>Listes des épisodes : </h3>";
        $i = 1;
        foreach($this->list->listEpisode as $track){
            $episodeRender = new EpisodeRender($track);
            $html .= "<p>" .$i.". ".$episodeRender->render($selector)."</p>";
            $i++;
        }
        $html.="</div>";

        $this->render = $html;
        return $this->render;
    }
}