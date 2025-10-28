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

    /**
     * Azok a változók, amiket a rendszer alapból használ
     * Ezek mindig nagybetűvel lesznek a set|get ben
     */
    protected const _SYSTEM_VARS = ["URL","UI","LOG","CLI"];
    public function __construct($config = null)
    {
        $this->checkHasRealConfig($config);
        $this->checkConfig($config);
    }

    use CallProtected;
    public function isSystemVar($name)
    {
        return in_array(strtoupper($name),self::_SYSTEM_VARS);
    }
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
        if($this->get("CLI")){
            return true;
        }
        foreach (self::_MINIMAL as $item) {
            $value = $this->get($item);
            if ($value == null) {
                return false;
            }
            $this->set($item,$this->checkSlash($value));
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
            $this->set($this->isSystem($name),$this->checkSlash($value));//Ha system var
        }
        return true;
    }

    protected function saveParams(&$config)
    {
        foreach ($config as $key => $value) {
            $this->set($this->isSystem($key), $value);
            $this->dirExists($key);
        }
    }
    protected function isSystem($name)
    {
        return (in_array($name,self::_SYSTEM_VARS)?strtoupper($name):$name);
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
    /**
     * @var string[] $_VARIABLES
     */
    private $_VARIABLES = [];
    /**
     * @param $name
     * @param $value
     * @return void
     */
    public function set($name,$value){
        if($this->isSystemVar($name)){
            $name = strtoupper($name);
        }
        $this->_VARIABLES[$name] = $value;
        return $value;
    }

    /**
     * @param $name
     * @return string|null
     */
    public function get($name){
        // $name = strtoupper($name);
        if($this->isSystemVar($name)){
            $name = strtoupper($name);
        }
        if(isset($this->_VARIABLES[$name]))
            return $this->_VARIABLES[$name];
        return null;
    }
}