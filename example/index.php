<?php

require __DIR__."/../vendor/autoload.php";
require __DIR__."/config.php";

$app = new Pachel\Light\src\Light();
$app::$Routing->add("/")->view();