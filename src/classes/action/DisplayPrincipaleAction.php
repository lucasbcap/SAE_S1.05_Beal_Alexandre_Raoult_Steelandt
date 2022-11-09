<?php

namespace iutnc\netvod\action;

use iutnc\netvod\db\ConnectionFactory;
use iutnc\netvod\render\CatalogueRender;
use iutnc\netvod\render\SerieRender;
use iutnc\netvod\video\Serie;

class DisplayPrincipaleAction extends \iutnc\netvod\action\Action
{


    public function __construct()
    {
        parent::__construct();
    }

    public function execute(): string
    {
        $res = "<h2>Liste de Favoris : </h2><br>";
        $user = unserialize($_SESSION['user']);
        if ($this->http_method == "GET") {
            $array = $user->getSQL("favori");
            if ($array!=null) {
                foreach ($array as $d) {
                    $serieCouranteRenderer = new CatalogueRender($d);
                    $res .= $serieCouranteRenderer->render(3);
                }
            }else{
                $res .= "Aucune série en favori";
            }

        }

        $res .= "<h2>Liste des séries en cours : </h2><br>";
        $user = unserialize($_SESSION['user']);
        if ($this->http_method == "GET") {


            $bdd = ConnectionFactory::makeConnection();
            $c1 = $bdd->prepare("select idSerie from encours where email = :email and idEpisode=1;");
            $mail = $user->email;
            $c1->bindParam(":email",$mail);
            $c1->execute();
            $array = null;
            while ($d = $c1->fetch()) {
                $serie = Serie::creerSerie($d['idSerie']);
                if ($serie!=null) {
                    $array[] = $serie;
                }
            }


            if ($array!=null) {
                foreach ($array as $d) {
                    $serieCouranteRenderer = new CatalogueRender($d);
                    $res .= $serieCouranteRenderer->render(2);
                }
            }else{
                $res .= "Aucune série en cours";

            }

        }

        $res .= "<h2>Liste des séries finies : </h2><br>";
        $user = unserialize($_SESSION['user']);
        if ($this->http_method == "GET") {
            $array = $user->getSQL("estfini");
            if ($array!=null) {
                foreach ($array as $d) {
                    $serieCouranteRenderer = new CatalogueRender($d);
                    $res .= $serieCouranteRenderer->render(3);
                }
            }else{
                $res .= "Aucune série en cours";

            }

        }

        return $res;
    }


}