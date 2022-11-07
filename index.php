<?php

use iutnc\netvod\db\ConnectionFactory;
use iutnc\netvod\Dispatcher\Dispatcher;

require_once 'vendor/autoload.php';

session_start();

ConnectionFactory::setConfig("config.ini");

$v = new \iutnc\netvod\Video\episode("test","test","test","test",0,0);
$vRender = new \iutnc\netvod\Render\EpisodeRender($v);

echo $vRender->render();

$html = new Dispatcher();
$html->run();
