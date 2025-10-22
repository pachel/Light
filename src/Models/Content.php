<?php

namespace Pachel\Light\src\Models;

class Content
{
    public $name;
    public $content;
    public function __construct($name,$content)
    {
        $this->name = $name;
        $this->content = $content;
    }
}