<?php

namespace iutnc\netvod\action;

use iutnc\netvod\db\ConnectionFactory;
use iutnc\netvod\render\CatalogueRender;
use iutnc\netvod\render\SerieRender;
use iutnc\netvod\video\Serie;

class DisplayCatalogueAction extends \iutnc\netvod\action\Action
{



    public function __construct()
    {
        parent::__construct();
    }

    public function execute(): string
    {
        $bdd = ConnectionFactory::makeConnection();
        $res = "";
        if ($this->http_method == "GET") {
            $res = "<h2>Catalogue : </h2> ";
            $c1 = $bdd->query("SELECT * from serie");
            $c1->execute();
            while ($d = $c1->fetch()) {
                $serie = new Serie($d['titre'],$d['img'], $d['genre'], $d['publicVise'], $d['descriptif'], $d['annee'],$d['date_ajout'], $d['id']);
                $render = new CatalogueRender($serie);
                $res .= $render->render();
            }
        }
        return $res;
    }




}