<?php

namespace Pachel\Light\src\Models;


use Pachel\EasyFrameWork\partObjectsItem;
use Pachel\Light\src\Light;

class Route
{
    private $_code;
    private $_code_return;
    private $_view;
    /**
     * Helyettesítő karakter *
     * Ha át kell adni a kódnak, akkor {name}
     * @var string $_path
     */
    private $_path;
    private $_variables = [];
    private $_methods = [];
    private $_isErrorPage = false;
    /**
     * @var Content[] $_contents
     */
    private $_contents = [];
    private $_layout = null;

    public function setErrorPage()
    {
        $this->_isErrorPage = true;
    }

    /**
     * Megmondja, hogy ez a method benne van e az engedélyezettek között
     * @param $method
     * @return bool
     */
    public function hasMethod($method)
    {
        return in_array(strtoupper($method), $this->_methods);
    }

    public function isErrorPage()
    {
        return $this->_isErrorPage;
    }

    public function addView($view)
    {
        $this->_view = $view;
    }

    public function getView()
    {
        return $this->_view;
    }

    public function addPath($path)
    {
        $this->_path = $path;
    }

    public function addMethods($methods)
    {

        if (preg_match("/webonly/i", $methods)) {
            $this->_methods = ["POST", "GET", "XHR"];
            return;
        }

        if (preg_match_all("/(post|get|ajax|cli)/i", $methods, $preg)) {
            foreach ($preg[0] as $value) {
                $this->_methods[] = strtoupper($value);
            }
        }
    }

    public function addCode($code)
    {
        $this->_code = $code;
    }

    public function getPath()
    {
        return $this->_path;
    }
    public function getMethods(){
        return $this->_methods;
    }

    public function getCode()
    {
        return $this->_code;
    }

    public function addVariables($vars)
    {
        $this->_variables = $vars;
    }

    public function getVariables()
    {
        return $this->_variables;
    }


    public function run()
    {
        if (empty($this->_code) || $this->_isErrorPage) {
            return null;
        }
        if(Light::$Routing->getActualRoute()->method == "CLI" && ob_get_level()>0){
            ob_end_flush();
        }
        switch (gettype($this->_code)) {
            case "string":
                return $this->runString();
            case  "object":
                return $this->runObject();
            case "array":
                return $this->runArray();
        }
    }

    private function runObject()
    {
        return $this->_code_return = ($this->_code)(...array_merge([Light::instance()], $this->_variables));
    }

    private function runArray()
    {
        return $this->runClass($this->_code[0], $this->_code[1]);
    }

    private function runString()
    {
        if (preg_match("/^(.+?)\->(.+)$/", $this->_code, $preg)) {
            return $this->runClass($preg[1], $preg[2]);
        }
        return null;
    }

    private function runClass($class, $method)
    {
        if (!class_exists($class) || !method_exists($class, $method)) {
            Light::instance()->setError(405);
            return null;
        }
        $c = new $class(Light::instance());
        return $c->{$method}(...$this->_variables);
    }

    /**
     * @return Content|void
     */
    private function getLayout()
    {
        foreach ($this->_contents as $content) {
            if ($content->name == "LAYOUT") {
                return $content;
            }
        }
    }

    public function getPage()
    {
        return $this->loadToLayout();

    }

    private function loadToLayout()
    {

        $layout = $this->getLayout();
        $this->replace_variables($layout->content);
        foreach ($this->_contents as $content) {
            if ($content->name == $layout->name) {
                continue;
            }
            $layout->content = preg_replace("/<!\-\-.*\[content:" . $content->name . "\].*\-\->/i", "<!--" . $content->name . "-->\n" . $content->content . "<!--END OF " . $content->name . "-->\n", $layout->content);
        }
        //  $this->run_content($layout->content);
        return $layout->content;

    }

    public function searchTags()
    {
        $file = Light::$Config->get("UI") . $this->_view;
        if (!is_file($file)) {
            throw new \Exception("Nincs meg a fájl: " . $file);//TODO: bele kell tenni a hibaüzenet szövegét
        }

        $content = file_get_contents($file);
        $this->loadMethods($content);
        if (!empty($this->_layout)) {
            $file = Light::$Config->get("UI") . $this->_layout;
            if (!is_file($file)) {
                throw new \Exception("Nincs meg a fájl: " . $file);//TODO: bele kell tenni a hibaüzenet szövegét
            }
            $content = file_get_contents($file);
            $this->loadMethods($content);
        }

    }

    private function loadMethods(&$content)
    {
        $this->setLoads($content);
        $this->cut_content($content);
    }

    private function cut_content($content)
    {
        $this->run_content($content);
        if (preg_match("/<!\-\-.*\[layout:(.+)\].*\-\->/i", $content, $preg)) {
            $this->_layout = $preg[1];
            $content = preg_replace("/<!\-\-.*\[layout:.+\].*\-\->/i", "", $content);//Kivesszük innen, már nem kell
        }
        if (preg_match_all("/<!\-\-\[name:(.+)\]\-\->(.+)/misU", $content, $preg)) {
            $splitted = "";
            for ($index = count($preg[0]) - 1; $index >= 0; $index--) {
                $splitted = explode($preg[0][$index], (is_array($splitted) ? $splitted[0] : $content));
                //$part->content = $splitted[1].($index==count($preg[0])-1?"\n":"");
                $content = $splitted[1] . ($index == count($preg[0]) - 1 ? "\n" : "");
                $this->_contents[] = new Content(strtoupper($preg[1][$index]), $content);
            }
        } else {
            $this->_contents[] = new Content("LAYOUT", $content);//Ha nem talál semit, akkor csak úgy hozzáadja a tartalmat
        }

    }

    private function setLoads(&$content)
    {
        $viewsDir = Light::$Config->get("UI");

        if (preg_match_all("/<!\-\-.*\[load:([a-z0-9_\-\/\.]+)\].*\-\->/i", $content, $preg)) {
            foreach ($preg[1] as $index => $value) {
                if (!file_exists($viewsDir . $value)) {
                    $content = str_replace($preg[0][$index], "<!--ERROR - file is not exists: " . $value . "-->", $content);
                    continue;
                }
                $load_content = file_get_contents($viewsDir . $value);
                //A html változók cseréje
                $this->replace_variables($load_content);
                //PHP tartalom futtatása
                $this->run_content($load_content);
                $name = preg_replace("/(.+)\.[^\.]+$/", "$1", basename($value));
                //$content = str_replace($preg[0][$index],"<!--".$name."-->\n".$load_content."\n<!--end of ".$name."-->\n",$content);
                $content = str_replace($preg[0][$index], $load_content, $content);
            }
        }


    }
    private $content = null;
    private function content(&$content = null)
    {
        if(is_null($this->content)){
            $this->content = &$content;
        }
        return $this->content;
    }

    private function run_content(&$content)
    {
        //$this->content($content);
        //unset($content);
        extract(Light::$Config->getAllVariables());
        /**
         * A lezáratlan php tegek lezárása
         */
        if (preg_match_all("/(<\?)|(\?>)/misU", $content, $preg)) {
            if (count($preg[0]) % 2 != 0) {
                unset($preg);
                $content .= "?>";
            }
        }
        unset($preg);
        error_reporting(E_ERROR);

        if (preg_match("/.+?\.php/i", $this->_view)) {
            //eval("\?\>" . $content . "<?php");
            eval("?>" . $content);
            $content = ob_get_clean();
        } else {

        }
        error_reporting(E_ALL);
        ob_start();
    }

    private function replace_variables(&$content)
    {
        if (preg_match_all("/\{\{([^\$\}]+)\}\}/", $content, $preg)) {
            foreach ($preg[1] as $index => $varname) {
                $variable = Light::$Config->get($varname);
                $content = str_replace($preg[0][$index], is_null($variable) ? "" : $variable, $content);
            }
        }
    }

}