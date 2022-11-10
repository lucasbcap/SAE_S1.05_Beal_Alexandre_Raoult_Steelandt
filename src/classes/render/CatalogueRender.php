<?php

namespace iutnc\netvod\render;

use iutnc\netvod\db\ConnectionFactory;
use iutnc\netvod\video\episode;
use iutnc\netvod\video\Serie;

/**
 * Render du catalogue
 */
class CatalogueRender extends Render
{
    protected Serie $serie;


    /**
     * Constructeur
     * @param Serie $serie Serie que l'on veut rendre sous forme de catalogue
     */
    public function __construct(Serie $serie)
    {
        $this->serie = $serie;
    }


    /**
     * Render de la série
     * @param int $selector selecteur de quel type d'affichage nous voulons, 1 pour le catalogue, 2 pour en cours et 3 pour les favories et fini
     * @return string affichage de la serie
     */
    public function render(int $selector = 1): string
    {
        $bdd = ConnectionFactory::makeConnection();

        $res = "";
        $id = $this->serie->id;

        //Affichage de la série dans le catalogue principale
        if($selector===1) {

            //Calcul de la moyenne
            $c2 = $bdd->prepare("select AVG(note) as moyenne from commentaire where  idSerie=?");
            $c2->bindParam(1, $id);
            $c2 ->execute();
            $moyenne = $c2->fetch()['moyenne'];
            if($moyenne === null) $moyenne = "Non notée";
            else $moyenne =round($moyenne,2) . " <img id ='stars' src='image/stars.png'>/ 5";

            //Affichage

            $res = "<div class='liste'><a href='?action=display-serie&id=" . $id . "' id='lienVid'><h2><center>" . $this->serie->titre ."</h2></a>";
            $res .= "";
            $res .= "<h3><center><a href='?action=display-commentaire&id=" . $this->serie->id . "' id='lien'>Note moyenne : $moyenne</a></h3>";

            $res .= "<center><a href='?action=display-serie&id=" . $this->serie->id . "' id='lien'><div class=zoom>
                    <div class=image2>
                    <img src='Image/" . $this->serie->img . "' width='600' height='380'></a></center><br>
                    </div>
                    </div>
                    </div>";

            //En favori ou non
            $array = unserialize($_SESSION['user'])->getSQL("favori");
            $trouve = false;
            if($array!=null) {
                foreach ($array as $serie) {
                    if ($this->serie->id === $serie->id) $trouve = true;
                }
            }
             if($trouve){
                 $res .= "<center><a href='?action=prefere&fav=oui&id=" . $this->serie->id . "'><img src='Image/coeurplein.png' width='70' height='70'></a></center>";

             }else {
                 $res .= "<center><a href='?action=prefere&fav=non&id=" . $this->serie->id . "'><img src='Image/coeurvide.png' width='70' height='70'></a></center>";
             }
        }

        //Affichage de la série dans en cours
        if($selector===2){

            $query = "select max(idEpisode) as epCourant from encours where idSerie=:numeroSerie";
            $c = $bdd->prepare($query);
            $id = $this->serie->id;
            $c->bindParam(":numeroSerie", $id);
            $c->execute();
            $numEp = $c->fetch()['epCourant'];

            $IdEp = Episode::chercherEpisodeNumero($numEp,$id);

            //Affichage
            $res = "<div class='liste'><a href='?action=display-serie&id=" . $id . "'>";
            $res .= "<h4>" . $this->serie->titre . "</h4>";
            $res .= "<a href='?action=display-episode&id=" . $IdEp . "' id='lien'><div class=zoom>
                    <div class=image>
                    <img src='Image/" . $this->serie->img . "' width='200' height='120'></a>
                    </div>
                    </div>
                    </div>";
        }

        //Affichage de la série dans favories ou fini, la différence est le lien qui ne sera pas le même
        if($selector===3){

            //Affichage
            $res = "<div class='liste'><a href='?action=display-serie&id=" . $id . "'>";
            $res .= "<h4>" . $this->serie->titre . "</h4>";
            $res .= "<a href='?action=display-serie&id=" . $id . "' id='lien'><div class=zoom>
                    <div class=image>
                    <img src='Image/" . $this->serie->img . "' width='200' height='120'></a>
                    </div>
                    </div>
                    </div>";
        }

        return $res;
    }
}