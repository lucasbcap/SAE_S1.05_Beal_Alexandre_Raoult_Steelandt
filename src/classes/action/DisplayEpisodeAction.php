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
                $res .= "
                <form id='profil' action='?action=sign-in' method='POST'>

                <label><b>Commentaire</b></label>
                <input type='text' placeholder='Entrer votre commentaire' name='commentaire' required height='100'><br>
        
                <input type='submit' id='log' value='LOGIN'>
                ";
                $user = unserialize($_SESSION['user']);
                $user->addSQL($episode->serie,"enCours");
            }
        }


        return $res;

    }
}