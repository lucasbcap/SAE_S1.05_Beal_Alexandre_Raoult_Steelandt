<?php

namespace iutnc\netvod\action;

use iutnc\netvod\auth\Auth;

class Deconnexion extends Action
{

    public function __construct()
    {
        parent::__construct();
    }

    public function execute(): string
    {
        $res ="";
        session_destroy();
        return $res;
    }
}