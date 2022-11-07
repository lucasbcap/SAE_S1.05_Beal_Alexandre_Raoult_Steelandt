<?php

namespace iutnc\netvod\action;

use iutnc\netvod\db\ConnectionFactory;
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
        $res="";
        if ($this->http_method == "GET") {
                $res = "<h2>Catalogue : </h2> ";
                $c1 = $bdd->query("SELECT titre,id from serie");
                $c1->execute();
                while ($data2 = $c1->fetch()) {
                    $res .= $data2['titre'];
                    $res .= "<a href='?action=display-serie&id=" . $data2['id'] . "'><img src='Image/beach.jpg' width='300' height='300'></a><br>";
                }
            }

        return $res;
    }




}