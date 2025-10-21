<?php

namespace Pachel\Light\src;

class Rendering
{
    private $_selectedRoutes = [];

    public function __construct()
    {
        $this->_selectedRoutes = Light::$Routing->searchRoutes();
        $this->runCodes();
    }
    private function runCodes(){
        foreach ($this->_selectedRoutes AS $index){
            Light::$Routing->getRoute($index)->run();
        }

    }
}