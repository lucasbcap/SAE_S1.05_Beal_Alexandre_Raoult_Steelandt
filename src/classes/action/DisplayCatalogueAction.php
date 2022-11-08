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
                $c1 = $bdd->query("SELECT titre,id,img from serie");
                $c1->execute();
                while ($data2 = $c1->fetch()) {
                    $res .= "<a href='?action=display-serie&id=" . $data2['id'] . "'>";
                    $res .= "<h4><center>". $data2['titre'] . "</h4>";
                    $res .= "<center><a href='?action=display-serie&id=" . $data2['id'] . "'><div class=zoom>
                    <div class=image>
                    <img src='Image/".$data2['img']."' width='600' height='380'></a></center><br>
                    </div>
                    </div>";

                }
            }

        return $res;
    }




}