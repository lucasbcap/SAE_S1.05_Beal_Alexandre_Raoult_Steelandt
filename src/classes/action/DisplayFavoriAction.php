<?php

namespace iutnc\netvod\action;

use iutnc\netvod\db\ConnectionFactory;
use iutnc\netvod\render\CatalogueRender;
use iutnc\netvod\render\SerieRender;
use iutnc\netvod\video\Serie;

class DisplayFavoriAction extends \iutnc\netvod\action\Action
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
                    $res .= $serieCouranteRenderer->render();
                }
            }else{
                $res = "Aucune Série en favorie";
            }

        }

        return $res;
    }


}