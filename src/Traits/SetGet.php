<?php
namespace Pachel\Light\src\Traits;
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
        $this->_VARIABLES[strtoupper($name)] = $value;
    }

    /**
     * @param $name
     * @return string|null
     */
    public function get($name){
        $name = strtoupper($name);
        if(isset($this->_VARIABLES[$name]))
            return $this->_VARIABLES[$name];
        return null;
    }
}
