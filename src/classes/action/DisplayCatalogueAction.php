<?php

namespace iutnc\netvod\action;

use iutnc\netvod\auth\Auth;
use iutnc\netvod\db\ConnectionFactory;
use iutnc\netvod\render\CatalogueRender;
use iutnc\netvod\user\User;
use iutnc\netvod\video\Serie;

class DisplayCatalogueAction extends \iutnc\netvod\action\Action
{



    public function __construct()
    {
        parent::__construct();
    }


    public function execute(): string
    {
        $res = "";
        if ($this->http_method == "GET") {
            if (isset($_GET['search'])) {
                $res = $this->afficherCatalogue($_GET['search']);
            }else{
                $res = $this->afficherCatalogue();
            }
        } else if ($this->http_method == "POST") {
            header('Location: ?action=display-catalogue&search='.$_POST['search']);
        }
        return $res;
    }

    public function afficherCatalogue(string $search = ""): string
    {
        $bdd = ConnectionFactory::makeConnection();
        $res = "";
        if ($this->http_method == "GET") {
            $res = "<h2>Catalogue : </h2> ";
            $rendu = "";
            $array = User::TrieSQL();
            if ($array!=null) {
                foreach ($array as $d) {
                    $serieCouranteRenderer = new CatalogueRender($d);
                    $res .= $serieCouranteRenderer->render(1);
                }
            }
            if ($search != "") {
                $res = $this->rechercher($search);
            }
        }
        return $res;


    }

    public function rechercher(string $search):string{
        $bdd = ConnectionFactory::makeConnection();
        $c1 = $bdd->query("SELECT * from serie where titre like '%$search%'");
        $c1->execute();
        $rendu = "";
        while ($d = $c1->fetch()) {
            $serie = new Serie($d['titre'], $d['img'], $d['genre'], $d['publicVise'], $d['descriptif'], $d['annee'], $d['date_ajout'], $d['id']);
            $render = new CatalogueRender($serie);
            $rendu .= $render->render();
        }
        if ($rendu == "") {
            $rendu = "<h3>Aucune série n'existe sous ce nom</h3>";
        }
        return $rendu;
    }
}