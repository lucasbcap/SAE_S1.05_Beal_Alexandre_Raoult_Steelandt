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
        $res ="";
        if($this->http_method == 'GET') $res = $this->affiche();
        else if ($this->http_method == 'POST') $res = $this->enregistrerCom();
        return $res;

    }

    public function affiche() : string{
        $res="";
        if ($this->http_method == "GET") {
            if(isset($_GET["id"])){
                $episode = Episode::chercherEpisode($_GET["id"]);
                $episodeRender = new EpisodeRender($episode);
                $res .= $episodeRender->render(1);
                $res .= "
                <form id='formPro' action='?action=sign-in' method='POST'>
                <label><b>Note</b></label>
                <select name='note'>
            <option value='tiret'>-</option>
            <option value='1'>1</option>
            <option value='2'>2</option>
            <option value='3'>3</option>
            <option value='4'>4</option>
            <option value='5'>5</option>
             </select>
              <br>
              <br>

                <label><b>Commentaire</b></label>
                <input id='input2' type='text' placeholder='Entrer votre commentaire' name='commentaire' required height='100'><br>
        
                <input type='submit' id='log' value='Envoyer'>
                ";
                $user = unserialize($_SESSION['user']);
                $user->addSQL($episode->serie,"enCours");
            }
        }

        return $res;
    }

    public function enregistrerCom() : string{
        $res="";
        if(isset($_POST['note']) && isset($_POST['commentaire'])){
            $bdd = ConnectionFactory::makeConnection();
            $note=$_POST['note'];
            $com = $_POST['commentaire'];
            $emailUser = unserialize($_SESSION['user'])->getEmail();
            $c2 = $bdd->prepare("INSERT INTO Commentaire values (?,?,?,)");

        }
        return $res;
    }

}