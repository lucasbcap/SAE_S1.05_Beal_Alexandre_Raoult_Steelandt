<?php

namespace iutnc\netvod\Dispatcher;

use iutnc\netvod\action\DisplayCatalogueAction;
use iutnc\netvod\action\SigninAction;
use iutnc\netvod\action\AddUserAction;

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
            case 'display-catalogue':
                $act = new DisplayCatalogueAction();
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
    <title>NetVOD</title>
    <meta charset='UTF-8' />
    <link rel='stylesheet' href='css/style.css'>
</head>
<header>
<nav>
    <li><a href='./'>Accueil</a></li>
    <li><a href='?action=sign-in'>Connexion</a></li>
    <li><a href='?action=add-user'>Inscription</a></li>
    <li><a href='?action=display-catalogue'>Afficher Catalogue</a></li>
</nav>
</header>
<body>
<h1>NetVOD</h1>
$res
</body>
</html>";

    }
}