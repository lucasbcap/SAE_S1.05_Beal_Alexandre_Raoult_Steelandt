<?php

namespace iutnc\netvod\action;

use iutnc\netvod\auth\Auth;
use iutnc\netvod\db\ConnectionFactory;
use iutnc\netvod\render\CatalogueRender;
use iutnc\netvod\user\User;
use iutnc\netvod\video\Episode;
use iutnc\netvod\video\Serie;

class DisplayCommentaireAction extends \iutnc\netvod\action\Action
{



    public function __construct()
    {
        parent::__construct();
    }


    public function execute(): string
    {
        $res = "<h3>Commentaires : </h3>";
        if ($this->http_method == "GET") {
            if (isset($_GET['id'])) {
                $bdd = ConnectionFactory::makeConnection();


                $c2 = $bdd->prepare("select email,note,comm from Commentaire where idSerie=?");
                $c2->bindParam(1, $_GET['id']);
                $c2->execute();
                $count = 0;
                while ($d = $c2->fetch()) {
                    if ($d['comm'] != null) {
                        $count++;
                        $res .= $d['email'] . "  Note " . $d['note'] . " sur 5 : <br>";
                        $res .= "<p STYLE='padding:0 0 0 20px'>".$d['comm'] . "</p><br><br>";
                    }
                }

                if($count===0) $res = "Pas de commentaires";

            }
        }
        return $res;
    }




}