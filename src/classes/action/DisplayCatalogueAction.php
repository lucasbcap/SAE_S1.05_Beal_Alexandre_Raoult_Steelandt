<?php

namespace iutnc\netvod\action;

use iutnc\netvod\db\ConnectionFactory;

class DisplayCatalogueAction extends \iutnc\netvod\action\Action
{

    public function __construct()
    {
        parent::__construct();
    }

    public function execute(): string
    {
        $res="<h1>Catalogue : </h1> ";
        $bdd = ConnectionFactory::makeConnection();
        $c1 = $bdd->query("SELECT titre from serie");
        $c1->execute();
        while($data  = $c1->fetch()){
            $res .= $data['titre'] . "\n";
        }

        return $res;
    }
}