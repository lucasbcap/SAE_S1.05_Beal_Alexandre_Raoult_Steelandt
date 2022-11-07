<?php

use iutnc\netvod\db\ConnectionFactory;
use iutnc\netvod\dispatcher\Dispatcher;

require_once 'vendor/autoload.php';

session_start();

ConnectionFactory::setConfig("config.ini");

$serie = \iutnc\netvod\video\Serie::creerSerie(1);

$serieRender = new \iutnc\netvod\render\SerieRender($serie);

echo $serieRender->render(1);

$html = new Dispatcher();
$html->run();
