<?php

error_reporting(E_ALL);
ini_set("display_errors",1);
require __DIR__."/../vendor/autoload.php";
require __DIR__."/config.php";


$app = new Pachel\Light\src\Light();
$app::$Routing->add("*",function (){ echo "Helló"; });//minden oldal
$app::$Routing->add("*",[MyController::class,"teszt"]);//minden oldal
$app::$Routing->add("*","MyController->teszt");//minden oldal

$app::$Routing->add("/")->view("test.html");//Kezdőoldal
$app::$Routing->add("/alma/{id}/{id2}.html",function ($id,$id2){ echo $id."\n"; echo $id2;});//regexes
$app::$Routing->add("/alma/{id}/{id2}.html","MyController->json","ajax")->json();

$app->run();