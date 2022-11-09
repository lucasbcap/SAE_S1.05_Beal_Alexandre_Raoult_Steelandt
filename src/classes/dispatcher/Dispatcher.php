<?php

namespace iutnc\netvod\dispatcher;

use iutnc\netvod\action\DisplayCatalogueAction;
use iutnc\netvod\action\DisplayCommentaireAction;
use iutnc\netvod\action\DisplayEpisodeAction;
use iutnc\netvod\action\DisplayPrincipaleAction;
use iutnc\netvod\action\DisplaySerieAction;
use iutnc\netvod\action\SigninAction;
use iutnc\netvod\action\AddUserAction;
use iutnc\netvod\action\ProfilAction;
use iutnc\netvod\action\PrefereAction;
use iutnc\netvod\action\MotDePasseOubAction;


class Dispatcher
{

    protected ?string $action = null;

    /**
     * @param string|null $action
     */
    public function __construct()
    {
        $this->action = $_GET['action'] ?? null;
    }

    public function run(): void
    {
        $act = $this->action;
        if(!isset($_SESSION['user']) && $act!='sign-in' && $act!='add-user' && $act!='mdpoub') {
            $html = "<p>Veuillez vous connecter pour accéder au catalogue</p>";
        }else {
            switch ($act) {
                case 'add-user':
                    $act = new AddUserAction();
                    $html = $act->execute();
                    break;
                case 'sign-in':
                    $act = new SigninAction();
                    $html = $act->execute();
                    break;
                case 'display-catalogue':
                    $act = new DisplayCatalogueAction();
                    $html = $act->execute();
                    break;
                case 'display-serie':
                    $act = new DisplaySerieAction();
                    $html = $act->execute();
                    break;
                case 'display-episode':
                    $act = new DisplayEpisodeAction();
                    $html = $act->execute();
                    break;
                case 'profil':
                    $act = new ProfilAction();
                    $html = $act->execute();
                    break;
                case 'prefere':
                    $act = new PrefereAction();
                    $html = $act->execute();
                    break;
                case 'display-commentaire':
                    $act = new DisplayCommentaireAction();
                    $html = $act->execute();
                    break;
                case 'mdpoub':
                    $act = new MotDePasseOubAction();
                    $html = $act->execute();
                    break;
                default:
                    $act = new DisplayPrincipaleAction();
                    $html = $act->execute();
                    break;
            }
        }
        print($this->renderPage($html));
    }


    private function renderPage(string $res): string
    {

        if(isset($_SESSION['user'])) {
            $search ="";
            if ($this->action == 'display-catalogue'){
                $search = "<form method='post' action='?action=display-catalogue'><li id='searchbar'><input size='30%' type ='search' 
                            name='search' placeholder='Rechercher une série'></li></form>";

                $search.="<form method='post' action='?action=display-catalogue'><ul id='btn'><li id='filtre'>
                           <button name='bnt1'>Trier</button>
                            <select name='filter'>
                            <option value='---'>---</option>
                            <option value='titre'>Titre</option>
                            <option value='genre'>Genre</option>
                            <option value='public vise'>Public Visé</option>
                            <option value='annee'>Année</option>
                            <option value='date ajout'>Date Ajout</option>
                            </select></li></ul></form>";
            }
            return "<!DOCTYPE html>
                    <html lang='fr'>    
                    <head>
                        <title>NetVOD</title>
                        <meta charset='UTF-8' />
                        <link rel='stylesheet' href='css/style.css'>
                    </head>
                    <header>
                    <ul>
                        <li><a href='./' id='Netvod'>NetVod</a></li>                    
                        <li><a href='?action=display-catalogue'>Afficher Catalogue</a></li>   
                        <li><a href='?action=profil'>Profil</a></li>  
                        $search
                             
                    </ul>
                    </header>
                    <body>
                    $res
                    </body>
                    </html>";
        }
        else{
            return "<!DOCTYPE html>
                    <html lang='fr'>
                    <head>
                        <title>NetVOD</title>
                        <meta charset='UTF-8' />
                        <link rel='stylesheet' href='css/style.css'>
                    </head>
                    <header>
                    <ul>
                        <li><a href='./' id='Netvod'>NetVod</a></li>
                        <li><a href='?action=sign-in'>Connexion</a></li>
                        <li><a href='?action=add-user'>Inscription</a></li>
                    </ul>
                    </header>
                    <body>
                    $res
                    </body>
                    </html>";
        }
    }
}