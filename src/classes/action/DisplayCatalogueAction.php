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
            }
            elseif(isset($_GET['trie'])){
                $res=$this->Filtre($_GET['trie']);
            }elseif(isset($_GET['filter'])) {
                $res = $this->Filtre($_GET['filter']);
            }else{
                $res .= $this->afficherCatalogue();
            }
        } else if ($this->http_method == "POST") {
            if(isset($_POST['search'])){
                header('Location: ?action=display-catalogue&search='.$_POST['search']);
            }

            if(isset($_POST['trie'])){
                header('Location: ?action=display-catalogue&trie='.$_POST['trie']);
            }
        }

        return $res;
    }

    public function afficherCatalogue(string $search =""): string
    {
        $res = "";
        if ($this->http_method == "GET") {
            $res = "<h2>Catalogue : </h2>";
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

    public function Filtre(string $filtre =""): string
    {
        if($filtre==="date ajout") $filtre = "date_ajout";
        if($filtre==="public vise") $filtre = "publicvise";
        if($filtre==="---") $filtre = null;
        $bdd = ConnectionFactory::makeConnection();
        $res = "";
        if ($this->http_method == "GET") {
            $res = "<h2>Catalogue : </h2>";
            $array = User::TrieSQL($filtre);
            if ($array!=null) {
                foreach ($array as $d) {
                    $serieCouranteRenderer = new CatalogueRender($d);
                    $res .= $serieCouranteRenderer->render(1);
                }
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