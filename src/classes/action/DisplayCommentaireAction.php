<?php

namespace iutnc\netvod\action;

use iutnc\netvod\db\ConnectionFactory;

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


                $c2 = $bdd->prepare("select commentaire.email,note,comm,titre,nom, prenom from commentaire inner join serie on commentaire.idSerie = serie.id
                             inner join user on commentaire.email = user.email where idSerie=?");
                $c2->bindParam(1, $_GET['id']);
                $c2->execute();
                $count = 0;
                $moy = 0;
                while ($d = $c2->fetch()) {
                    if ($d['comm'] != null) {
                        $count++;                       // compte le nombre de commentaire
                        $titre = $d['titre'];           //recupere les differents attributs necessaire a l'affichage
                        $compte = $d['email'];
                        if ($d['nom']!=""){
                            $compte = $d['nom']." ";
                        }
                        if ($d['prenom']!=""){
                            $compte .= $d['prenom'];
                        }
                        $comm .= "<div id='commentaire'><h2> ".$compte . "  Note " . $d['note'] . " <img id ='stars' src='Image/stars.png'>/ 5 : </h2>";
                        $comm .= "<p STYLE='padding:0 0 0 10px'>".$d['comm'] . "</p><br><br></div>";        // Affichage de la personne qui commente, de la note et de son commentaire
                        $moy+=$d['note'];
                    }
                }

                if($count===0){                 // si pas de commentaires
                    $res = "<h2>Pas de commentaires</h2>";
                }else{
                    // sinon on calcul la moyenne et on l'affiche
                    $moy/=$count;
                    $moy = round($moy,2);
                    $res .="<h3><div id='note'> Moyenne pour la série '".$titre."' : ".$moy." <img id ='stars' src='Image/stars.png'>/5 <p>Commentaires :</p> </div></h3>";
                    $res .= $comm;                  
                }

            }
        }
        return $res;
    }




}