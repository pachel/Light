<?php

namespace Pachel\Light\src;

use Pachel\Light\src\Models\Route;

class Rendering
{
    private $_selectedRoutes = [];
    private $_code_content = null;
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
            $this->_code_content = Light::$Routing->getRoute($index)->run();
            if(!empty($this->_code_content)){
                $this->printConntent($this->_code_content,Light::$Routing->getRoute($index)->getView());
                Light::instance()->setError(-1);
                return;
            }
        }
        if(!empty($this->_code_content)){
            Light::instance()->setError(-1);
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
        $e = "";
        foreach ($this->_selectedRoutes as $index) {
            $view = Light::$Routing->getRoute($index)->getView();
            if(empty($view)){
                continue;
            }
            $e.="1";
            $content = $this->Render(Light::$Routing->getRoute($index));

            $this->printConntent($content,$view);
        }
        if (empty($e)) {
             return false;
        }
        return true;
    }

    public function printConntent(&$content, $view)
    {
        if (!$this->isDirectContent($view)){
            if(empty($content)){
                Light::instance()->setError(204);
                return;
            }
            echo $content;
            return;

        }
        switch (mb_strtoupper($view,"UTF-8")){
            case "JSON": $this->printJson($content);break;
            case "CSV": $this->printCsv($content);break;
            default: print_r($content);
        }

    }
    private function printCsv($content){
        header('Access-Control-Allow-Origin: *');
        header("Content-Type: text/csv; charset=utf-8");
        //TODO: A csv-t meg kell csinálni
    }
    private function printJson($content){
        header('Access-Control-Allow-Origin: *');
        header("Content-type: application/json; charset=utf-8");
        echo json_encode($content,JSON_PRETTY_PRINT);
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