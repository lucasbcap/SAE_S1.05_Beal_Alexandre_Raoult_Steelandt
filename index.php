<?php

use iutnc\netvod\db\ConnectionFactory;
use iutnc\netvod\Dispatcher\Dispatcher;

require_once 'vendor/autoload.php';

session_start();

ConnectionFactory::setConfig("config.ini");

$v = new \iutnc\netvod\vidéo\Video("test","test","test","test",0,0);
$vRender = new \iutnc\netvod\Render\VideoRender($v);

echo $vRender->render();

$html = new Dispatcher();
$html->run();
