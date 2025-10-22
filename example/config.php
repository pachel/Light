<?php
if(file_exists(__DIR__."/dev_config.php")){
    include __DIR__."/dev_config.php";
    define("K_KEX",1);
}
else {
    define("LFW_URL", "http://localhost/light/example/");
//define("LFW_URL","http://light.local/");
    define("LFW_UI", __DIR__ . "/ui");

    /*
    return [
        "url"=>"",
        "ui"=>"",
        "logdir"=>""//Opcionális
    ];
    */
}