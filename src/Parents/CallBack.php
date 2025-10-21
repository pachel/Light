<?php

namespace Pachel\Light\src\Parents;
abstract class CallBack
{
    protected $ParentClass;
    public function __construct($class)
    {
        $this->ParentClass = $class;
    }
    public function __call(string $name, array $arguments)
    {
        if(!method_exists($this->{$name})){
            return $this->ParentClass->{$name};
        }
    }
}