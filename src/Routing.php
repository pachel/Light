<?php

namespace Pachel\Light\src;

class Routing
{
    private $_url;
    private $_routes = [];
    public function __construct()
    {
        $this->_url = Light::instance()::$Config->get("url");
    }


    /**
     * @param $path
     * @param string $methods [GET|POST|CLI|AJAX,ALL,WEBONLY]
     * @return void
     */
    public function add($path,$methods = "WEBONLY"){

    }

}