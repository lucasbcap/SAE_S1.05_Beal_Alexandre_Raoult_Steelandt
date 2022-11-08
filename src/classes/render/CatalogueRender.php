<?php

namespace iutnc\netvod\render;

use iutnc\netvod\db\ConnectionFactory;
use iutnc\netvod\video\episode;
use iutnc\netvod\video\Serie;

class CatalogueRender extends Render
{
    protected Serie $serie;

    public function __construct(Serie $serie)
    {
        $this->serie = $serie;
    }

    public function render(int $selector = 1): string
    {
        $bdd = ConnectionFactory::makeConnection();
        $c2 = $bdd->prepare("select AVG(note) as moyenne from Commentaire where  idSerie=?");
        $id = $this->serie->id;
        $c2->bindParam(1, $id);
        $c2 ->execute();
        $moyenne = $c2->fetch()['moyenne'];
        if($moyenne === null) $moyenne = "Non notée";
        else $moyenne =round($moyenne,2) . " sur 5";

        $res = "";
        if($selector===1) {
            $res = "<div class='liste'><a href='?action=display-serie&id=" . $id . "'>";
            $res .= "<h4><center>" . $this->serie->titre ."</h4>";
            $res .= "<h4><center><a href='?action=display-commentaire&id=" . $this->serie->id . "' id='lien' <a>Note moyenne : $moyenne</h4>";


            $res .= "<center><a href='?action=display-serie&id=" . $this->serie->id . "' id='lien'><div class=zoom>
                    <div class=image>
                    <img src='Image/" . $this->serie->img . "' width='600' height='380'></a></center><br>
                    </div>
                    </div>
                    </div>";
            $array = unserialize($_SESSION['user'])->getSQL("favori");
            $trouve = false;
            if($array!=null) {
                foreach ($array as $serie) {
                    if ($this->serie->id === $serie->id) $trouve = true;
                }
            }
             if($trouve){
                 $res .= "<center><a href='?action=prefere&id=" . $this->serie->id . "'><img src='Image/coeurplein.png' width='70' height='70'></a></center>";
             }else {
                 $res .= "<center><a href='?action=prefere&id=" . $this->serie->id . "'><img src='Image/coeurvide.png' width='70' height='70'></a></center>";
             }
        }

        if($selector===2){
            $res = "<div class='liste'><a href='?action=display-serie&id=" . $this->serie->id . "'>";
            $res .= "<h4>" . $this->serie->titre . "</h4>";
            $res .= "<a href='?action=display-serie&id=" . $this->serie->id . "' id='lien'><div class=zoom>
                    <div class=image>
                    <img src='Image/" . $this->serie->img . "' width='200' height='120'></a>
                    </div>
                    </div>
                    </div>";
        }

        return $res;
    }
}