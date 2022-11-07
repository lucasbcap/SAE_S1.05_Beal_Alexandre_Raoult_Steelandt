<?php

namespace iutnc\netvod\dispatch;

use iutnc\deefy\action\DisplayPlaylistAction;
use iutnc\deefy\action\SigninAction;
use iutnc\deefy\action\AddUserAction;

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
        switch ($this->action) {
            case 'add-user':
                $act = new AddUserAction();
                $html = $act->execute();
                break;
            case 'sign-in':
                $act = new SigninAction();
                $html = $act->execute();
                break;
            default:
                $html = '<h2>Bienvenue !</h2>';
                break;
        }
        print($this->renderPage($html));
    }


    private function renderPage(string $res): string
    {
        return "<!DOCTYPE html>
<html lang='fr'>
<head>
    <title>Deefy</title>
    <meta charset='UTF-8' />
</head>
<body>
<h1>Deefy</h1>
<nav>
    <li><a href='./'>Accueil</a></li>
    <li><a href='?action=add-playlist'>Creer une playlist</a></li>
    <li><a href='?action=sign-in'>Connexion</a></li>
    <li><a href='?action=add-user'>Inscription</a></li>
    <li><a href='?action=display-playlist'>Afficher Playlist</a></li>
</nav>
        $res
</body>
</html>";

    }
}