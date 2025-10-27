<?php

use Pachel\Light\src\Light;

error_reporting(E_NOTICE | E_ERROR | E_WARNING);
ini_set("display_errors", 1);
require __DIR__ . "/../vendor/autoload.php";
require __DIR__ . "/config.php";

$app = Light::instance();
$app::$Routing->add("*", function ($app) {


    $app->set("ALWAYS_RUNNING", "Always running");
});//minden oldal
$app::$Routing->add("/")->view("single.html");//Kezdőoldal
$app::$Routing->add("/function", function ($app) {
    $app->set("myVariable", "My variable");
})->view("function.php");
$app::$Routing->add("/class", [MyController::class, "teszt"])->view("class.php");
$app::$Routing->add("/string", "MyController->string")->view("layout.php");

$app::$Routing->add("cli*", function ($app,$elso) {
    echo $elso;
}, "cli");//minden oldal
//$app::$Routing->add("*",[MyController::class,"teszt"]);//minden oldal
//$app::$Routing->add("*","MyController->teszt");//minden oldal
$app::$Routing->add("/php",null,"get|post|ajax")->view("multi.php");
$app::$Routing->add("/multihtml")->view("multi.html");
$app::$Routing->add("/demo")->view("demo.html");
$app::$Routing->add("/json",function (){
    return ["return"=>true];
})->json();
$app::$Routing->add("/csv",function (){
    $row[] = [1,25,47,33];
    $row[] = [1,20,40,33];
    return $row;
})->csv();
$app::$Routing->add("/empty")->view("empty.html");
//$app::$Routing->add("/demo")->view("demo.html");
$app::$Routing->add("/reroute",function ($app){
    $app->reroute("/php");

})->view("multi.html");

$app::$Routing->add("/product/{productname}.html", function ($app,$var) {
    $app->set("_name",$var);

})->view("product.php");

$app::$Auth->policy()->deny();
$app::$Auth->allow("/php");
$app::$Auth->AuthMethod(function ($app,$url){
    return true;
});
$app->run();