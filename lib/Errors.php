<?php

namespace Pachel\libs;

use Pachel\Light\src\Models\ErrorClass;

class Errors
{
    public static $_MESSAGE = [
        1001 => "Nincs meg a minimál config! ",
        1002 => "A mappa nem létezik, vagy nem mappa: %s! "
    ];

    /**
     * @param ...$params
     * @return void
     */
    public static function getMessage(...$params)
    {
        return str_replace("%s",$params[1],self::$_MESSAGE[$params[0]]);
    }
}