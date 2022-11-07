<?php

namespace iutnc\netvod\action;

use iutnc\netvod\db\ConnectionFactory;
use iutnc\netvod\render\SerieRender;
use iutnc\netvod\video\Serie;

class DisplaySerieAction extends \iutnc\netvod\action\Action
{



    public function __construct()
    {
        parent::__construct();
    }

    public function execute(): string
    {
        $bdd = ConnectionFactory::makeConnection();
        $res="";
        if ($this->http_method == "GET") {
            if(isset($_GET["id"])){
                $serieCourante = Serie::creerSerie($_GET['id']);
                $serieCouranteRenderer = new SerieRender($serieCourante);
                $res .= $serieCouranteRenderer->render(2);

            }
        }

        return $res;
    }




}