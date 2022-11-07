<?php

namespace iutnc\netvod\action;

use iutnc\netvod\render\EpisodeRender;
use iutnc\netvod\render\SerieRender;
use iutnc\netvod\video\Episode;
use iutnc\netvod\video\Serie;

class DisplayEpisodeAction extends \iutnc\netvod\action\Action
{

    public function __construct()
    {
        parent::__construct();
    }

    public function execute(): string
    {

        $res="";
        if ($this->http_method == "GET") {
            if(isset($_GET["id"])){
                $episode = Episode::chercherEpisode($_GET["id"]);
                $episodeRender = new EpisodeRender($episode);
                $res .= $episodeRender->render(1);

            }
        }

        return $res;

    }
}