<?php

use iutnc\netvod\db\ConnectionFactory;
use iutnc\netvod\Dispatcher\Dispatcher;

require_once 'vendor/autoload.php';

session_start();

ConnectionFactory::setConfig("config.ini");

$v = new \iutnc\netvod\Video\videotrack\episode("test","video/beach.mp4","Image/beach.jpg","test",0,0);
$vRender = new \iutnc\netvod\Render\EpisodeRender($v);

echo $vRender->render(1);

$html = new Dispatcher();
$html->run();
