<?php

namespace Pachel\Light\src;

class Light
{
    /**
     * @var Config $_config
     */
    private $_config;

    public function __construct($config = null)
    {
        $this->_config = new Config($config);
    }
}