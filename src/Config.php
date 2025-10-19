<?php

namespace Pachel\Light\src;

class Config
{
    /**
     * @var string[] $_VARIABLES
     */
    private $_VARIABLES = [];
    /**
     * Azok a config paraméterek, amik biztosan mappák.
     * Ezeket majd ellenőrizzük, hogy léteznek-e
     */
    private const _DIRS = ["log","ui"];
    /***
     * Kötelező paraméterek, amik nélkül ne fog menni az egész
     * @const
     */
    private const _MINIMAL = ["url","ui"];
    public function __construct($config = null)
    {
        new \Exception("");
    }
    private function checkConfig(&$config):bool{

    }
}