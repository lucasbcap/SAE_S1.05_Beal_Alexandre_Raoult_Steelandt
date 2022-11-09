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
                $res=$this->Trie($_GET['trie']);
            }elseif(isset($_GET['genre']) && isset($_GET['type'])) {
                $res = $this->Filtre($_GET['type'],$_GET['genre']);
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

            if(isset($_POST['filtre1']) && isset($_POST['filtre2'])){
                header('Location: ?action=display-catalogue&type='.$_POST['filtre1'].'&genre='.$_POST['filtre2']);
            }
        }

        return $res;
    }

    public function afficherCatalogue(string $search =""): string
    {
        $res = "";
        if ($this->http_method == "GET") {
            $res = "<h1>Catalogue : </h1>";
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

    public function Filtre(string $type ="" , string $genre=""): string
    {

        $res = "";
        if ($this->http_method == "GET") {
            $res = "<h2>Catalogue : </h2>";


            if($genre !=="genreF" && $type !=="public viseF")$array = Serie::SerieArgs($genre,$type);
            elseif ($genre !=="genreF") $array = Serie::SerieArgs($genre);
            elseif ($type !=="public viseF")$array = Serie::SerieArgs("",$type);


            if ($array!=null) {
                foreach ($array as $d) {
                    $serieCouranteRenderer = new CatalogueRender($d);
                    $res .= $serieCouranteRenderer->render(1);
                }
            }
            else{
                header('Location: ?action=display-catalogue');
            }
        }
        return $res;
    }

    public function Trie(string $trie =""): string
    {
        if($trie==="date ajout") $trie = "date_ajout";
        if($trie==="public vise") $trie = "publicvise";
        if($trie==="---") $trie = null;

        $res = "";
        if ($this->http_method == "GET") {
            $res = "<h2>Catalogue : </h2>";
            if($trie!=="moyenne") {
                $array = User::TrieSQL($trie);
            }
            else{
                $query = "select idSerie from commentaire group by idSerie ORDER BY avg(note) DESC ";
                $bdd = ConnectionFactory::makeConnection();
                $c1 = $bdd->prepare($query);
                $c1->execute();
                $array = null;
                while ($d = $c1->fetch()) {
                    $serie = Serie::creerSerie($d["idSerie"]);
                    if ($serie!=null) {
                        $array[] = $serie;
                    }
                }

                $query = "select id from serie where id not IN (select idSerie from commentaire); ";
                $c1 = $bdd->prepare($query);
                $c1->execute();
                while ($d = $c1->fetch()) {
                    $serie = Serie::creerSerie($d["id"]);
                    if ($serie!=null) {
                        $array[] = $serie;
                    }
                }
            }
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
        $c1 = $bdd->prepare("SELECT * from serie where titre like :s");
        $search = "%".$search."%";
        $c1->bindParam(":s",$search);
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