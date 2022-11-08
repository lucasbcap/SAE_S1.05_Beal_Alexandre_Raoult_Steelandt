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
                <form id='sign' action='?action=sign-in' method='POST'>
                <h1>Commentaire</h1>

                <label><b>Email</b></label>
                <input type='email' placeholder='Entrer votre mail' name='mail' required><br>
        
                <input type='submit' id='log' value='LOGIN'>
                ";
                $user = unserialize($_SESSION['user']);
                $user->addSQL($episode->serie,"enCours");
            }
        }


        return $res;

    }
}