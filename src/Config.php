<?php

namespace Pachel\Light\src;

use Pachel\libs\Errors;
use Pachel\Light\src\Traits\CallProtected;
use Pachel\Light\src\Traits\SetGet;

class Config
{

    /**
     * Azok a config paraméterek, amik biztosan mappák.
     * Ezeket majd ellenőrizzük, hogy léteznek-e
     */
    protected const _DIRS = ["LOG", "UI"];
    /***
     * Kötelező paraméterek, amik nélkül ne fog menni az egész
     * @const
     */
    protected const _MINIMAL = ["URL", "UI"];

    public function __construct($config = null)
    {
        $this->checkHasRealConfig($config);
        $this->checkConfig($config);
    }

    use SetGet;
    use CallProtected;
    protected function checkHasRealConfig(&$config){
        if(empty($config)){
            $defs = get_defined_constants(true);
            foreach ($defs["user"] AS $key=>$def){
                if(preg_match("/LFW_(.+)/i",$key,$preg)){
                    $config[$preg[1]] = $def;
                }
            }
        }
    }

    protected function checkConfig(&$config)
    {
        $this->saveParams($config);
        if (!$this->hasMinimal($config)) {
            throw new \Exception(Errors::$_MESSAGE[1001], 1001);
        }
    }

    protected function hasMinimal(&$config)
    {

        foreach (self::_MINIMAL as $item) {
            if ($this->get($item) == null) {
                return false;
            }
            $this->set($item,$this->checkSlash($this->get($item)));
        }
        return true;
    }

    protected function dirExists($name)
    {
        $value = $this->get($name);
        if (in_array(strtoupper($name), self::_DIRS)) {
            if (!is_dir($value)) {
                throw new \Exception(Errors::getMessage(1002, $value), 1002);
            }
            $this->set($name,$this->checkSlash($value));
        }
        return true;
    }

    protected function saveParams(&$config)
    {
        foreach ($config as $key => $value) {
            $this->set($key, $value);
            $this->dirExists($key);
        }
    }

    protected function checkSlash($string)
    {
        if (substr($string, strlen($string) - 1, 1) == "/") {
            return $string;
        }
        return $string . "/";
    }
    public function getAllVariables(){
        return $this->_VARIABLES;
    }
}