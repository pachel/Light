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

    private $_errorCode = 0;

    use errorCodes;
    public function __construct($config = null)
    {
        self::$Config = new Config($config);
        self::$Routing = new Routing();
    }
    public function run()
    {
        $render = new Rendering();
        if($this->_errorCode == 0) {
            $render->showPage();
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

        if($this->_errorCode == 0){
            return;
        }
        ob_clean();
        header(self::HTTPStatus($this->_errorCode)["error"], true, $this->_errorCode);
        exit();
    }
    public function get($name)
    {
        return self::$Config->get($name);
    }
    public function set($name,$value)
    {
        return self::$Config->set($name,$value);
    }
}