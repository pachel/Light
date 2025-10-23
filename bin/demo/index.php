<?php

use Pachel\Light\src\Light;

define("LFW_URL", "http://localhost/light");
define("LFW_UI", __DIR__ . "/ui");
define("LFW_TITLE","This is myWebpage");

require __DIR__ . "/../vendor/autoload.php";
$app = Light::instance();
$app::$Routing->add("*")->view("demo.html");
$app->run();

