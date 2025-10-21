<?php

namespace Pachel\Light\src\Models;

class Route
{
    private $_code;
    private $_code_return;
    private $_view;
    /**
     * Helyettesítő karakter *
     * Ha át kell adni a kódnak, akkor {name}
     * @var string $_path
     */
    private $_path;
    private $_variables = [];
    private $_methods = [];

    public function addView($view)
    {

    }

    public function addPath($path)
    {
        $this->_path = $path;
    }

    public function addMethods($methods)
    {
        //TODO:
    }

    public function addCode($code)
    {
        $this->_code = $code;
    }

    public function getPath()
    {
        return $this->_path;
    }

    public function getCode()
    {
        return $this->_code;
    }

    public function addVariables($vars)
    {
        $this->_variables = $vars;
    }

    public function getVariables()
    {
        return $this->_variables;
    }

    public function run()
    {
        if (empty($this->_code)) {
            return null;
        }
        switch (gettype($this->_code)) {
            case "string":
                return $this->runString();
            case  "object":
                return $this->runObject();
            case "array":
                return $this->runArray();
        }
    }

    private function runObject()
    {
        return $this->_code_return = ($this->_code)(...$this->_variables);
    }

    private function runArray()
    {
        return $this->runClass($this->_code[0],$this->_code[1]);
    }
    private function runString(){
        if(preg_match("/^(.+?)\->(.+)$/",$this->_code,$preg)){
            return $this->runClass($preg[1],$preg[2]);
        }
        return null;
    }
    private function runClass($class,$method){
        $c = new $class($this);
        return $c->{$method}(...$this->_variables);
    }
}