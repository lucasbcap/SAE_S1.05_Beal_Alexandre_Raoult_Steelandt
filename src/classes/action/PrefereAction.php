<?php

namespace iutnc\netvod\action;

use iutnc\netvod\user\User;

class PrefereAction extends \iutnc\netvod\action\Action
{

    public function __construct()
    {
        parent::__construct();
    }

    public function execute(): string
    {
        $res = "";
        unserialize($_SESSION['user'])->addSQL($_GET['id'],"favori");
        header("Location:?action=display-catalogue");
        return "";

    }
}