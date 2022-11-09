<?php

namespace iutnc\netvod\action;

use iutnc\netvod\db\ConnectionFactory;
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
        else if ($this->http_method == 'POST') {
            $res = $this->affiche();
            $this->enregistrerCom();
        }
        return $res;

    }

    public function affiche() : string{
        $bdd = ConnectionFactory::makeConnection();
        $res="";

        if(isset($_GET["id"])){
            $episode = Episode::chercherEpisode($_GET["id"]);
            $episodeRender = new EpisodeRender($episode);
            $res .= $episodeRender->render(1);
            $res .= "
            <form id='formPro' action='?action=display-episode&id=".$_GET["id"]."' method='POST'>
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
            <input id='input2' type='text' placeholder='Entrer votre commentaire' name='commentaire' height='100'><br>
    
            <input type='submit' id='log' value='Envoyer'>
            ";
            $user = unserialize($_SESSION['user']);
            $user->addSQL($episode->serie,"enCours",$episode->numero);
        }
        return $res;
    }

    public function enregistrerCom(){
        if(isset($_POST['note']) && DisplayEpisodeAction::notable()){
            $com = null;
            if(isset($_POST['commentaire'])){
                $com = $_POST['commentaire'];
            }
            $bdd = ConnectionFactory::makeConnection();
            $note=$_POST['note'];
            $chercherSerie = Episode::chercherSerie($_GET['id']);
            $emailUser = unserialize($_SESSION['user'])->getEmail();

            $c2 = $bdd->prepare("INSERT INTO commentaire values (?,?,?,?)");
            $c2->bindParam(1,$com);
            $c2->bindParam(2,$note);
            $c2->bindParam(3,$emailUser);
            $c2->bindParam(4, $chercherSerie);
            $c2 ->execute();

        }
    }

    public static function notable():bool{
        $bdd = ConnectionFactory::makeConnection();

        $chercherSerie = Episode::chercherSerie($_GET['id']);
        $emailUser = unserialize($_SESSION['user'])->getEmail();

        $c2 = $bdd->prepare("select * from commentaire where email=? and idSerie=?");
        $c2->bindParam(1,$emailUser);
        $c2->bindParam(2, $chercherSerie);
        $c2 ->execute();

        $verif = true;
        if($c2->fetch()){
            $verif = false;
        }
        return $verif;
    }

}