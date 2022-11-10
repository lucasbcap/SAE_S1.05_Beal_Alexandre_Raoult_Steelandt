<?php

namespace iutnc\netvod\video;

use iutnc\netvod\db\ConnectionFactory;

/**
 * Classe serie
 */
class Serie
{

    /**
     * @var array listEpisode ce qui represente une liste d'episode
     * @var string titre, genre, publicVise, descriptif, sortie, dateAjout, img cela represente les caractéristique d'une serie
     * @var int nmbEpisode, id ce qui represente le nombre d episode et l id de la serie
     */
    protected array $listEpisode;
    protected string $titre, $genre, $publiqueVise, $descriptif, $sortie, $dateAjout,$img;
    protected int $nmbEpisode;
    protected int $id;

    /**
     * Constructeur de la classe serie afin de pouvoir creer une liste de series
     * @param array $listEpisode cela represente une liste de series
     * @param string $titre cela represente le titre d une serie
     * @param string $genre cela represente le genre d une serie
     * @param string $publiqueVise cela represente le public qui est vise pour une serie
     * @param string $descriptif cela represente le descriptif d une serie
     * @param string $sortie cela represente l annee de sortie d une serie
     * @param string $dateAjout cela represente la date d ajout d une serie
     * @param int $nmbEpisode cela represente le nombre d episode dans une serie
     */
    public function __construct(string $titre, string $img, string $genre, string $publiqueVise, string $descriptif, string $sortie, string $dateAjout,int $id, array $listEpisode = [])
    {
        $this->listEpisode = $listEpisode;
        $this->img = $img;
        $this->titre = $titre;
        $this->genre = $genre;
        $this->publiqueVise = $publiqueVise;
        $this->descriptif = $descriptif;
        $this->sortie = $sortie;
        $this->dateAjout = $dateAjout;
        $this->id = $id;
        $this->nmbEpisode = count($listEpisode);
    }

    /**
     * Methode ajouterEpisode qui permet d ajouter un episode a une serie
     * @param Episode $d cela represente l episode qu on souhaite ajouter dans la serie
     * @return void
     */
    public function ajouterEpisode(Episode $d): void
    {
        $this->nmbEpisode++;
        $this->listEpisode[$this->nmbEpisode] = $d;
    }

    /**
     * Methode creerSerie qui permet de creer une serie avec un id en parametre
     * @param int $id cela represente l id de la serie quand elle sera creee
     * @return Serie retourne la serie qui a ete creee
     */
    public static function creerSerie(int $id): Serie
    {
        $bdd = ConnectionFactory::makeConnection();
        $req1 = $bdd->prepare("Select * from serie where id=:id"); //requete sql afin de récupérer toutes les valeurs d'une serie en fonction de l'id dans la base de données
        $req1->bindParam(":id", $id);
        $req1->execute();
        $d = $req1->fetch();
        //on enregistre les données de la base de données dans la serie
        $serie = new Serie($d['titre'],$d['img'], $d['genre'], $d['publicVise'], $d['descriptif'], $d['annee'],$d['date_ajout'], $d['id']);

        //permet de recuperer les episodes en fonction de l'id d'une série et de les ajouter
        $req2 = $bdd->prepare("Select episode.id from serie inner join episode on serie.id = episode.serie_id where serie.id=:id");
        $req2->bindParam(":id", $id);
        $req2->execute();
        while ($d = $req2->fetch()) {
            $ep = Episode::chercherEpisode($d['id']);
            $serie->ajouterEpisode($ep);
        }
        return $serie;
    }

    /**
     * Methode SerieArgs cela permet de
     * @param string $genre
     * @param string $type
     * @return array|null
     */
    static function SerieArgs(string $genre="", string $type=""): ?array
    {
        if($genre !=="" && $type !=="") $query = "select id from serie where genre = :args1 and publicVise = :args2";
        elseif ($genre !=="")$query = "select id from serie where genre = :args1";
        elseif ($type !=="")$query = "select id from serie where publicVise = :args2";

        $bdd = ConnectionFactory::makeConnection();
        $c1 = $bdd->prepare($query);
        if ($genre !=="") $c1->bindParam(":args1",$genre);
        if ($type !=="")  $c1->bindParam(":args2",$type);
        $c1->execute();
        $array = null;
        while ($d = $c1->fetch()) {
            if(isset($d["id"])) {
                $serie = Serie::creerSerie($d["id"]);
                if ($serie != null) {
                    $array[] = $serie;
                }
            }
        }
        return $array;
    }


    /**
     * Get magique de la classe
     * @param string $at cela represente represente un nom d attribut de la classe
     * @return mixed cela renvoie n importe quel type
     * @throws \Exception
     */
    public function __get(string $at): mixed
    {
        if (property_exists($this, $at)) {
            return $this->$at;
        } else {
            throw new \Exception("$at: invalid property");
        }
    }

}