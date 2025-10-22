<?php
namespace Pachel\Light\src\Traits;
use Pachel\Light\src\Config;
use Pachel\Light\src\Light;

trait SetGet{
    /**
     * @var string[] $_VARIABLES
     */
    private $_VARIABLES = [];
    /**
     * @param $name
     * @param $value
     * @return void
     */
    public function set($name,$value){
        if(Light::$Config->isSystemVar($name)){
            $name = strtoupper($name);
        }
        $this->_VARIABLES[$name] = $value;
        return $value;
    }

    /**
     * @param $name
     * @return string|null
     */
    public function get($name){
        // $name = strtoupper($name);
        if(Light::$Config->isSystemVar($name)){
            $name = strtoupper($name);
        }
        if(isset($this->_VARIABLES[$name]))
            return $this->_VARIABLES[$name];
        return null;
    }
}
