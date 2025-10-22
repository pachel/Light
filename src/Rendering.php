<?php

namespace Pachel\Light\src;

use Pachel\Light\src\Models\Route;

class Rendering
{
    private $_selectedRoutes = [];
    /**
     * @var string[] Azoka view nevek, amiknél csak a tartalom lesz generálva json|csv
     */
    private $_direct_types = ["JSON", "CSV"];//Ennek nagybetűvel kell lennie

    public function __construct()
    {
        $this->_selectedRoutes = Light::$Routing->searchRoutes();
        if (empty($this->_selectedRoutes)) {
            Light::instance()->setError(404);
        }
        $this->runCodes();
    }

    /**
     * Futtatja a talált routokban a kódokat
     * @return void
     */
    private function runCodes()
    {
        foreach ($this->_selectedRoutes as $index) {
            Light::$Routing->getRoute($index)->run();
        }
    }

    private function isDirectContent($view)
    {
        return in_array($view, $this->_direct_types);
    }

    /**
     * @return Models\Route|void
     * @throws \Exception
     */
    private function contentRendering()
    {

    }

    public function showPage()
    {
        foreach ($this->_selectedRoutes as $index) {
            $view = Light::$Routing->getRoute($index)->getView();
            $content = $this->Render(Light::$Routing->getRoute($index));
            $this->printConntent($content,$view);
            if (!empty($view)) {
                return;
            }
        }
    }

    public function printConntent(&$content, $view)
    {
        if (!$this->isDirectContent($view)){
            echo $content;
            return;

        }
        switch (mb_strtoupper($view,"UTF-8")){

        }
        echo $content;
    }

    /**
     * @param Route $route
     * @return string|array
     */
    private function Render($route)
    {
        if (empty($route->getView())) {
            return null;
        }
        $route->searchTags();
        return $route->getPage();
    }
}