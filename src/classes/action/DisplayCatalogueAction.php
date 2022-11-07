<?php

namespace iutnc\netvod\action;

class DisplayCatalogueAction extends \iutnc\netvod\action\Action
{

    public function __construct()
    {
        parent::__construct();
    }

    public function execute(): string
    {
        $res="<h1>Catalogue : </h1> ";


        return $res;
    }
}