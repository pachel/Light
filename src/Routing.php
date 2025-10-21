<?php

namespace Pachel\Light\src;

use http\Message;
use Pachel\libs\Errors;
use Pachel\Light\src\Models\Route;
use Pachel\Light\src\Parents\CallBack;

class Routing
{
    private $_url;
    /**
     * @var Route[] $_routes
     */
    private $_routes = [];

    public function __construct()
    {
        $this->_url = Light::$Config->get("url");
    }


    /**
     * @param $path
     * @param string|array $controller Ez a kontroller, ami lefut
     * @param string $methods [GET|POST|CLI|AJAX,ALL,WEBONLY]
     * @return addCallback
     */
    public function add($path, $controller = null, $methods = "WEBONLY")
    {
        $r = new Route();
        $r->addPath($path);
        $r->addCode($controller);
        $r->addMethods($methods);
        $this->_routes[] = $r;
        return new addCallback($this);
    }

    private function getTextToRegex($path,&$variables = null)
    {
        if(preg_match_all("/\{([^\}]+)\}/",$path,$preg)){
            $search = [];
            $replace = [];
            foreach ($preg[1] AS $value){
                $variables[] = $value;
                $search[] = "{".$value."}";
                $replace[] = "__".$value."__";
                $replace2[] = "(.+?)";

            }
            $path = str_replace($search,$replace,$path);
            $path = preg_quote($path,"/");
            return str_replace($replace,$replace2,$path);

        }
        return preg_quote($path, "/");
    }

    public function getActualRoute()
    {
        //TODO: ide majd be kell tenni a cli-t
        $alap = dirname($_SERVER["SCRIPT_NAME"]);
        if(!preg_match("/".$this->getTextToRegex($alap)."(.*)/",$_SERVER["REQUEST_URI"],$preg)){
            throw new \Exception(Errors::getMessage(2001),2001);
        }
        return $preg[1];
    }
    public function searchRoutes()
    {
        $actualRoute = $this->getActualRoute();
      //  echo $actualRoute;
        $find = false;
        foreach ($this->_routes AS $route){
            $variables = [];
            if(preg_match("/^".$this->getTextToRegex($route->getPath(),$variables)."$/",$actualRoute,$preg)){
                print_r($variables);
                $find = true;
            }
        }

    }
}

class addCallback extends CallBack
{

}