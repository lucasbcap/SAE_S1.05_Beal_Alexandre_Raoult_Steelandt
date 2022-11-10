<?php

namespace iutnc\netvod\action;

use iutnc\netvod\db\ConnectionFactory;
use iutnc\netvod\render\EpisodeRender;
use iutnc\netvod\video\Episode;


/**
 * class qui gere la gestion des episodes donc sont affichage mais aussi les commentaires
 */

class DisplayEpisodeAction extends \iutnc\netvod\action\Action
{

    /**
     * Constructeur classique
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * la methode appeller par le dispatcher
     * @return string se qu il faut afficher
     */
    public function execute(): string
    {
        // on regarde si un commentaire est poster ou pas si oui on l enregistre
        if ($this->http_method == 'POST') {
            $this->enregistrerCom();
        }
        // dans tout les cas on affiche l episode
        $res = $this->affiche();
        return $res;

    }

    /**
     * Permet d afficher un episode et la zone de commentaire en dessous
     * @return string
     */
    public function affiche() : string{
        $bdd = ConnectionFactory::makeConnection();
        $res="";

        if(isset($_GET["id"])){
            // on cherche l episode suivant l id / voir fonction dans episode
            $episode = Episode::chercherEpisode($_GET["id"]);

            // on affiche l episode puis la zone de saisie des commentaires
            $episodeRender = new EpisodeRender($episode);
            $res .= $episodeRender->render(1);
            $res = $this->afficheCom($res);

            // on ajoute l episode a la table encours
            $user = unserialize($_SESSION['user']);
            $user->addSQL($episode->serie,"encours",$episode->numero);

            $idSerie = Episode::chercherEpisode($_GET['id'])->serie;

            //on regarde si un grace a cette episode la series est finie
            if($this->estFini($idSerie)){

                //si oui on regarde si la serie est deja dans la table est finis

                $c4 =$bdd->prepare("SELECT count(*) as id2 from estfini where idSerie =? and email =?;");
                $c4->bindParam(1,$idSerie);
                $mail = $user->email;
                $c4->bindParam(2,$mail);
                $c4->execute();
                $verif=true;
                while($c4->fetch()){
                    $verif = false;
                }
                // et on supprime la serie poour le user dans la table en cours
                $user->suppSQL($idSerie, "encours");

                // si non on l ajoute la serie a estFini
                if($verif) {
                    $c5 = $bdd->prepare("INSERT INTO estfini values(?,?)");
                    $c5->bindParam(1, $mail);
                    $c5->bindParam(2, $idSerie);
                    $c5->execute();
                }
            }
        }

        return $res;
    }


    /**
     * Regarde si une serie est fini
     * @param int $idSerie serie a regarder
     * @return bool true si fini false sinon
     */
    public function estFini(int $idSerie):bool{
        $bdd = ConnectionFactory::makeConnection();
        $user = unserialize($_SESSION['user']);

        // on regarde le nombre d episode total de la serie en parametre
        $c3 =$bdd->prepare("SELECT count(id) as id from episode where serie_id = ?");
        $c3->bindParam(1,$idSerie);
        $c3->execute();
        while($da = $c3->fetch()){
            $i= $da['id'];
        }

        // on regarde le nombre d episode dans encours de la serie en parametre
        $c4 =$bdd->prepare("SELECT count(idEpisode) as id2 from encours where idSerie =? and email =?;");
        $c4->bindParam(1,$idSerie);
        $mail = $user->email;
        $c4->bindParam(2,$mail);
        $c4->execute();
        while($d = $c4->fetch()){
            $j= $d['id2'];
        }

        // si les 2 nombres sont egaux alors la serie est fini
        return $i==$j;
    }

    /**
     * fonction qui permet d afficher la zone ou taper son commentaire
     * @param string $res l affichage d avant
     * @return string la zone de commentaire
     */
    public function afficheCom(string $res):string{
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
        return $res;
    }

    /**
     * Function qui enregistre un commentaire dans la base
     * @return void
     */
    public function enregistrerCom(){
        // si le commentaire n a pas deja ete ecrie pour la serie et l user courant
        if(isset($_POST['note']) && DisplayEpisodeAction::notable()){

            // alors on l ajoute
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

    /**
     * function qui permet de savoir si un commentaire a deja ete ecrie
     * @return bool false si ecrie true sinon
     */
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