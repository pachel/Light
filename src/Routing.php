<?php

namespace Pachel\Light\src;

use http\Message;
use Pachel\libs\Errors;
use Pachel\Light\src\Models\Route;
use Pachel\Light\src\Parents\CallBack;
use Pachel\Light\src\Traits\CallProtected;

class Routing
{
    private $_url;
    /**
     * @var Route[] $_routes
     */
    private $_routes = [];
    /**
     * @var actualRoute $_actualRoute
     */
    private $_actualRoute;
    /**
     * Ide betesszük a protected methódus meghívót, hogy kívülről működjön
     */
    use CallProtected;

    public function __construct()
    {
        $this->_url = Light::$Config->get("URL");
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

    /**
     * Ez a függvény lényegében átalakítja a Routhoz tartozó path értékét, hogy illeszkedjen
     * az url-re a regex keresésnél. Itt bekerült a {} és a * is.
     * A {} egy bármi, amit majd a kontroller megkap paraméterként
     * @param $path
     * @param $variables
     * @return array|string|string[]
     */
    private function getTextToRegex($path, &$variables = null)
    {
        $csillag = ["______", ".*?"];
        $path = str_replace("*", $csillag[0], $path);
        if (preg_match_all("/\{([^\}]+)\}/", $path, $preg)) {
            $search = [];
            $replace = [];
            foreach ($preg[1] as $value) {
                $variables[] = $value;
                $search[] = "{" . $value . "}";
                $replace[] = "__" . $value . "__";
                $replace2[] = "(.*?)";

            }
            $path = str_replace($search, $replace, $path);
            $path = preg_quote($path, "/");
            $replace[] = $csillag[0];
            $replace2[] = $csillag[1];
            return str_replace($replace, $replace2, $path);

        }
        return str_replace($csillag[0], $csillag[1], preg_quote($path, "/"));
    }

    /**
     * Visszaadja az URL-t, amiben már nincs benne a felesleges sallang
     * @return actualRoute
     * @throws \Exception
     */
    public function getActualRoute()
    {

        if(!is_null($this->_actualRoute)){
            return $this->_actualRoute;
        }

        if(isset($_SERVER["argv"]) && $_SERVER["argc"]>1){
            $this->_actualRoute = new actualRoute($_SERVER["argv"][1],"CLI");
            return $this->_actualRoute;
        }

        $alap = str_replace("\\", "/", dirname($_SERVER["SCRIPT_NAME"]));//Windowsnál visszaper jön, ha domainként fut
        $url = $_SERVER["REQUEST_URI"];
        if(preg_match("/\?/",$url)){//H avan get paramáter ,akkor arra is figlyelni kell
            $l = explode("?",$url);
            $url = $l[0];
        }
        if (!preg_match("/" . $this->getTextToRegex($alap) . "(.*)/", $url, $preg)) {
            Light::instance()->setError(404);
            return null;
            //throw new \Exception(Errors::getMessage(2001), 2001);
        }
        $return = new actualRoute();
        $return->method=$_SERVER["REQUEST_METHOD"];
        $return->route = (empty($preg[1]) ? "/" : (substr($preg[1],0,1)!="/"?"/".$preg[1]:$preg[1]));//Azért kell, mert domain-nál így működik csak

        $this->_actualRoute = $return;
        return $this->_actualRoute;
    }

    /**
     * Visszaadja az url-re illeszkedő routokat
     * @return int[]
     * @throws \Exception
     */
    public function searchRoutes()
    {
        $this->getActualRoute();

        $actualRoute = $this->_actualRoute;
        //TODO: ezen a ponton kell beépíteni a jogosultságkezelést
        $selected = [];

        foreach ($this->_routes as $index => $route) {
            $variables = [];
            //echo $this->getTextToRegex($route->getPath(), $variables)."\n";
            //A \/* azért kell, mert mappánál így a / jel is lehet a vége
            if (preg_match("/^" . $this->getTextToRegex($route->getPath(), $variables) . "\/*$/", $actualRoute->route, $preg) && $route->hasMethod($actualRoute->method)) {
                if($actualRoute->method == "CLI" && $_SERVER["argc"]>2){
                    $variables = $_SERVER["argv"];
                    array_shift($variables);
                    array_shift($variables);
                    $route->addVariables($variables);
                }
                elseif(!empty($variables)) {
                    array_shift($preg);
                    $route->addVariables($preg);//A paramétereket hozáadjuk
                }
                $selected[] = $index;
            }
        }
        if(count($selected) == 0){
            Light::instance()->setError(404);
        }
        return $selected;
    }

    protected function addView($ui_file)
    {
        $this->_routes[count($this->_routes) - 1]->addView($ui_file);
    }
    protected function json(){
        $this->_routes[count($this->_routes) - 1]->addView("json");
    }
    protected function csv(){
        $this->_routes[count($this->_routes) - 1]->addView("csv");
    }

    /**
     * @param $index
     * @return Route
     */
    public function getRoute($index)
    {
        //print("getRoute:".count($this->_routes))."\n";
        if(isset($this->_routes[$index])){
            return $this->_routes[$index];
        }
    }
}

class addCallback extends CallBack
{
    public function view($ui_file){
        return $this->ParentClass->addView($ui_file);
    }
    public function json(){
        return $this->ParentClass->json();
    }
    public function csv(){
        return $this->ParentClass->csv();
    }
}
class actualRoute{
    public $route;
    public $method;
    public function __construct($route = null,$method = null)
    {
        if(!is_null($route)){
            $this->route = $route;
        }
        if(!is_null($method)){
            $this->method = $method;
        }
    }
}