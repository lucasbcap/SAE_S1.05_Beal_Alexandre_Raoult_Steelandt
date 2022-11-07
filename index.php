<?php

use iutnc\netvod\db\ConnectionFactory;
use iutnc\netvod\dispatcher\Dispatcher;

require_once 'vendor/autoload.php';

session_start();

ConnectionFactory::setConfig("config.ini");

$v = new \iutnc\netvod\video\Episode("test","video/beach.mp4","Image/beach.jpg","test",0,0);
$vRender = new \iutnc\netvod\render\EpisodeRender($v);

echo $vRender->render(1);

$html = new Dispatcher();
$html->run();
