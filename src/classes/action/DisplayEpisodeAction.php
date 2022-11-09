<?php

namespace iutnc\netvod\action;

use iutnc\netvod\db\ConnectionFactory;
use iutnc\netvod\render\EpisodeRender;
use iutnc\netvod\render\SerieRender;
use iutnc\netvod\user\User;
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
            $user->addSQL($episode->serie,"encours",$episode->numero);

            $idSerie = Episode::chercherEpisode($_GET['id'])->serie;

            $c3 =$bdd->prepare("SELECT count(id) as id from episode where serie_id = ?");
            $c3->bindParam(1,$idSerie);
            $c3->execute();
            while($da = $c3->fetch()){
                $i= $da['id'];
            }


            $c4 =$bdd->prepare("SELECT count(idEpisode) as id2 from encours where idSerie =? and email =?;");
            $c4->bindParam(1,$idSerie);
            $mail = $user->email;
            $c4->bindParam(2,$mail);
            $c4->execute();
            while($d = $c4->fetch()){
                $j= $d['id2'];
            }

            if($i === $j){
                $user->suppSQL($idSerie,"encours");
                $c5 = $bdd->prepare("INSERT INTO estfini values(?,?)");
                $c5->bindParam(1,$mail);
                $c5->bindParam(2,$idSerie);
                $c5->execute();


            }
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
            $emailUser = unserialize($_SESSION['user'])->email;

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
        $emailUser = unserialize($_SESSION['user'])->email;

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