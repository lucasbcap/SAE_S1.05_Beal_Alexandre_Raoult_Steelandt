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
        $res = "";
        $comm = "";
        if ($this->http_method == "GET") {
            if (isset($_GET['id'])) {
                $titre = "";
                $bdd = ConnectionFactory::makeConnection();


                $c2 = $bdd->prepare("select commentaire.email,note,comm,titre,nom, prenom from commentaire inner join Serie on commentaire.idSerie = serie.id
                             inner join user on commentaire.email = user.email where idSerie=?");
                $c2->bindParam(1, $_GET['id']);
                $c2->execute();
                $count = 0;
                $moy = 0;
                while ($d = $c2->fetch()) {
                    if ($d['comm'] != null) {
                        $count++;
                        $titre = $d['titre'];
                        $compte = $d['email'];
                        if ($d['nom']!=""){
                            $compte = $d['nom']." ";
                        }
                        if ($d['prenom']!=""){
                            $compte .= $d['prenom'];
                        }
                        $comm .= "<div id='commentaire'><h2> ".$compte . "  Note " . $d['note'] . " <img id ='stars' src='image/stars.png'>/ 5 : </h2>";
                        $comm .= "<p STYLE='padding:0 0 0 10px'>".$d['comm'] . "</p><br><br></div>";
                        $moy+=$d['note'];
                    }
                }

                if($count===0){
                    $res = "<h2>Pas de commentaires</h2>";
                }else{
                    $moy/=$count;
                    $res .="<h3><div id='note'> Moyenne pour la série '".$titre."' : ".$moy." <img id ='stars' src='image/stars.png'>/5 <p>Commentaires :</p> </div></h3>";
                    $res .= $comm;
                }

            }
        }
        return $res;
    }




}