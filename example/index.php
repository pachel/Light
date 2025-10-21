<?php

error_reporting(E_ALL);
ini_set("display_errors",1);
require __DIR__."/../vendor/autoload.php";
require __DIR__."/config.php";


$app = new Pachel\Light\src\Light();
$app::$Routing->add("/");//Kezdőoldal
$app::$Routing->add("/alma/{id}/{id2}.html");//Kezdőoldal

$app->run();