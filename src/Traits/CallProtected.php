<?php
namespace Pachel\Light\src\Traits;
use PhpParser\Builder\Trait_;

trait CallProtected{
    public function __call(string $name, array $arguments)
    {
        if(method_exists($this,$name)){
            return $this->$name(...$arguments);
        }
    }
}