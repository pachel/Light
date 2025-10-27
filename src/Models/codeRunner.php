<?php

namespace Pachel\Light\src\Models;

use Pachel\Light\src\Light;

class codeRunner
{
    private $_code;
    private $_variables = [];
    public function __construct($code)
    {
        $this->_code = $code;
    }

    public function run()
    {

        switch (gettype($this->_code)) {
            case "string":
                return $this->runString();
            case  "object":
                return $this->runObject();
            case "array":
                return $this->runArray();
        }
    }
    public function addVariables($variables)
    {
        $this->_variables = $variables;
    }

    private function runObject()
    {
      return ($this->_code)(...array_merge([Light::instance()], $this->_variables));
    }

    private function runArray()
    {
        return $this->runClass($this->_code[0], $this->_code[1]);
    }

    private function runString()
    {
        if (preg_match("/^(.+?)\->(.+)$/", $this->_code, $preg)) {
            return $this->runClass($preg[1], $preg[2]);
        }
        return null;
    }

    private function runClass($class, $method)
    {
        if (!class_exists($class) || !method_exists($class, $method)) {
            Light::instance()->setError(405);
            return null;
        }
        $c = new $class(Light::instance());
        return $c->{$method}(...$this->_variables);
    }
}