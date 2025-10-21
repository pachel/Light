<?php

namespace Pachel\Light\src;

use Pachel\Light\src\Parents\Prefab;

class Light extends Prefab
{
    /**
     * @var Config $_config
     */
    public static $Config;
    /**
     * @var Routing $Routing
     */

    public static $Routing;

    public function __construct($config = null)
    {
        self::$Config = new Config($config);

        self::$Routing = new Routing();
    }
    public function run()
    {
        $render = new Rendering();
    }
}