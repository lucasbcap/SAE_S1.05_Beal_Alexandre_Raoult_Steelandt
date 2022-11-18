<?php

namespace iutnc\netvod\action;

use iutnc\netvod\db\ConnectionFactory;
use iutnc\netvod\render\CatalogueRender;
use iutnc\netvod\video\Serie;

class DisplayPrincipaleAction extends \iutnc\netvod\action\Action
{


    public function __construct()
    {
        parent::__construct();
    }

    public function execute(): string
    {
        $res = "<h2>Liste de Favoris : </h2><br>";              // va afficher la liste des favoris
        $user = unserialize($_SESSION['user']);
        if ($this->http_method == "GET") {
            $array = $user->getSQL("favori");                   // on recupere tout les id des serie dans favori
            if ($array!=null) {
                $res .= "<div class='listeGeneral'>";
                foreach ($array as $d) {
                    $serieCouranteRenderer = new CatalogueRender($d);
                    $res .= $serieCouranteRenderer->render(3);          // on affiche pour chaque leur titre et leur image
                }
                $res .= "</div>";
            }else{
                $res .= "Aucune série en favori";
            }

        }

        $res .= "<h2>Liste des séries en cours : </h2><br>";            // va afficher les series en cours
        $user = unserialize($_SESSION['user']);
        if ($this->http_method == "GET") {

            $bdd = ConnectionFactory::makeConnection();
            $c1 = $bdd->prepare("select idSerie from encours where email = :email and idEpisode=1;");           // on recupere les id series en cours pour l'utilisateur connecte qui a commencer a regarder le premier episode
            $mail = $user->email;
            $c1->bindParam(":email",$mail);
            $c1->execute();
            $array = null;
            while ($d = $c1->fetch()) {
                $serie = Serie::creerSerie($d['idSerie']);              //on creer les serie grace a leur id
                if ($serie!=null) {
                    $array[] = $serie;                  // on les met dans un tableau
                }
            }

            if ($array!=null) {
                $res .= "<div class='listeGeneral'>";
                foreach ($array as $d) {                        // on parcours toutes les series
                    $serieCouranteRenderer = new CatalogueRender($d);
                    $res .= $serieCouranteRenderer->render(2);              // on les affiche en recuperant l'episode en cours
                }
                $res .= "</div>";

            }else{
                $res .= "Aucune série en cours";

            }

        }

        $res .= "<h2>Liste des séries finies : </h2><br>";              // affichage des series finies
        $user = unserialize($_SESSION['user']);
        if ($this->http_method == "GET") {
            $array = $user->getSQL("estfini");                          // on recupere les id series dans estfini
            if ($array!=null) {
                $res .= "<div class='listeGeneral'>";
                foreach ($array as $d) {                                // pour chaque series
                    $serieCouranteRenderer = new CatalogueRender($d);
                    $res .= $serieCouranteRenderer->render(3);      // on affiche son titre et son image
                }
                $res .= "</div>";
            }else{
                $res .= "Aucune série en cours";

            }

        }

        return $res;
    }


}