<?php

namespace iutnc\netvod\dispatcher;

use iutnc\netvod\action\Deconnexion;
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
        if (!isset($_SESSION['user']) && $act != 'sign-in' && $act != 'add-user' && $act != 'mdpoub') {
            $html = "<div class='grayscale'><div id='st'></dib><img src='image/logo.png' id='bienvenue'></div></div><h1 id='wel'>Bienvenue</h1>          ";
        } else {
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
                case 'deconnexion':
                    $act = new Deconnexion();
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

        if (isset($_SESSION['user'])) {
            $search = "";
            if ($this->action == 'display-catalogue') {
                $search = "<div id='catalogue'><form method='post' action='?action=display-catalogue'><li id='searchbar'><input size='30%' type ='search' 
                            name='search' placeholder='Rechercher une série'></li></form>";

                $search .= "<form method='post' action='?action=display-catalogue'><li id='trie'>
                            <select name='trie'>
                            <option value='---'>---</option>
                            <option value='titre'>Titre</option>
                            <option value='genre'>Genre</option>
                            <option value='public vise'>Public Visé</option>
                            <option value='annee'>Année</option>
                            <option value='date ajout'>Date Ajout</option>
                            </select>
                            <button name='bnt1'>Trier</button>
                            </li></form>";

                $search .= "<form method='post' action='?action=display-catalogue'><li id='filtre'>

                            <select name='filtre1'>
                            <option value='public viseF'>Type de publique</option>
                            <option value='adulte'>Adulte</option>
                            <option value='famille'>Famille</option>
                            <option value='adolescent'>Adolescent</option>
                            </select>
                            
                            <select name='filtre2'>
                            <option value='genreF'>Genre</option>
                            <option value='horreur'>Horreur</option>
                            <option value='action'>Action</option>
                            <option value='aventure'>Aventure</option>
                            <option value='sport'>Sport</option>
                            <option value='nostalgie'>Nostalgie</option>
                            </select>
                            <button name='bnt1'>Filtré</button>
                            </li>
                            </form></div>";


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
                        <div id='logodiv'><li><a href='./' id='logo'><img src='image/logo.png' id='logo'></a></li></div>                 
                        <li><a href='?action=display-catalogue' id='navbar'>Afficher Catalogue</a></li>   
                        <li><a href='?action=profil' id='navbar'>Profil </a></li>  
                        <li><a href='?action=deconnexion' id='navbar'>Deconnexion</a></li>
                        $search
                             
                    </ul>
                    </header>
                    <body>
                    $res
                    </body>
                    </html>";
        } else {
            return "<!DOCTYPE html>
                    <html lang='fr'>
                    <head>
                        <title>NetVOD</title>
                        <meta charset='UTF-8' />
                        <link rel='stylesheet' href='css/style.css'>
                    </head>
                    <header>
                    <ul>
                        <div id='logodiv'><li><a href='./' id='logo'><img src='image/logo.png' id='logo'></a></li></div>        
                        <li><a href='?action=sign-in' id='navbar'>Connexion</a></li>
                        <li><a href='?action=add-user' id='navbar'>Inscription</a></li>
                    </ul>
                    </header>
                    <body>
                    $res
                    </body>
                    </html>";
        }
    }
}