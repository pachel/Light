<?php
if(file_exists(__DIR__."/dev_config.php")){
    include __DIR__."/dev_config.php";
}
else {
    define("LFW_URL", "http://localhost/light");
    define("LFW_UI", __DIR__ . "/ui");

    /*
    return [
        "url"=>"",
        "ui"=>""
    ];
    */
}
define("LFW_TITLE","This is myWebpage");