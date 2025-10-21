<?php

namespace Pachel\Light\src\Models;

class Route
{
    private $_codes = [];
    private $_view;
    /**
     * Helyettesítő karakter *
     * Ha át kell adni a kódnak, akkor {name}
     * @var string $_path
     */
    private $_path;
    private $_methods = [];
    public function addView($view)
    {

    }
    public function addPath($path)
    {
        $this->_path = $path;
    }
    public function addMethods($methods)
    {
        //TODO:
    }
    public function addCode($code)
    {
        $this->_codes[] = $code;
    }
    public function getPath()
    {
        return $this->_path;
    }
}