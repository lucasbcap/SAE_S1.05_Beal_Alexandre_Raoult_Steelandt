<?php

use iutnc\netvod\db\ConnectionFactory;
use iutnc\netvod\dispatch\Dispatcher;

require_once 'vendor/autoload.php';

session_start();

ConnectionFactory::setConfig("config.ini");

$html = new Dispatcher();
$html->run();
