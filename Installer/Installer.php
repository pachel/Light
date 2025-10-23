<?php

namespace Pachel\Light\Install;

class Installer
{

    private static $root;
    public static function postInstall()
    {
        self::$root = __DIR__."/../../../";
        copy(__DIR__."/.htaccess",self::$root.".htaccess");

    }

    public static function postUpdate()
    {
        echo "POST UPDATE";
    }
}