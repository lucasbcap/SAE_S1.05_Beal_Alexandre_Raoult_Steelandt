<?php

namespace iutnc\netvod\action;

/**
 * Classe PrefereAction qui extends Action
 */
class PrefereAction extends \iutnc\netvod\action\Action
{

    /**
     * methode magique
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Methode execute qui permet d executer les methodes suppSQL et addSQL pour rajouter des series favoris
     * @return string retourne une chaine comportant les informations à mettre sur le site
     */
    public function execute(): string
    {
        $res = "";
        if($_GET['fav'] == 'oui'){
            unserialize($_SESSION['user'])->suppSQL($_GET['id'], "favori");

        }else {
            unserialize($_SESSION['user'])->addSQL($_GET['id'], "favori");
        }
        header("Location:?action=display-catalogue");
        return "";
    }
}