<?php

namespace Pachel\Light\src;

use Pachel\Light\src\Parents\Prefab;
use Pachel\Light\src\Traits\errorCodes;

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
    /**
     * @var Auth $Auth
     */

    public static $Auth;

    private $_errorCode = 0;

    use errorCodes;

    public function __construct($config = null)
    {
        self::$Config = new Config($config);
        self::$Routing = new Routing();
        self::$Auth = new Auth();
    }

    public function run()
    {

        $render = new Rendering();

        if ($this->_errorCode == 0) {
            if (!$render->showPage()) {
                $this->setError(404);
            }
        }
        $this->getError();
    }

    //private $_errorCode = null;
    public function setError($http_code)
    {
        $this->_errorCode = $http_code;
    }

    public function getError()
    {

        if ($this->_errorCode <= 0 || self::$Routing->getActualRoute()->method == "CLI") {
            return;
        }
        if (ob_get_level() > 0) {
            ob_clean();
        }
        header(self::HTTPStatus($this->_errorCode)["error"], true, $this->_errorCode);
        exit();
    }

    public function get($name)
    {
        return self::$Config->get($name);
    }

    public function set($name, $value)
    {
        return self::$Config->set($name, $value);
    }

    public function reroute($route)
    {
        $url = preg_replace("#([^:])//#", "$1/", $this->get("url") . $route);
        header("location:" . $url);
        exit();
    }

    /**
     * @param string $name
     * @param string $value
     * @return void
     */
    public function env(...$params)
    {

        if (count($params) == 1) {
            return self::$Config->get($params[0]);
        }
        if (count($params) == 2) {
            return $this->set($params[0], $params[1]);
        }
    }
}