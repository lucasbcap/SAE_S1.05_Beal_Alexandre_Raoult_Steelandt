<?php

namespace iutnc\netvod\action;

use iutnc\netvod\db\ConnectionFactory;
use iutnc\netvod\render\SerieRender;
use iutnc\netvod\video\Serie;

/**
 * Classe DisplaySerieAction qui extends la classe Action
 */
class DisplaySerieAction extends \iutnc\netvod\action\Action
{


    /**
     * Methode magique le constructeur
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Methode execute qui permet d executer les methodes creees dans cette classe
     * @return string retourne une chaine comportant les informations à mettre dans le html
     */
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