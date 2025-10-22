<?php

class MyController
{
    /**
     * @var \Pachel\Light\src\Light $app
     */
    private $app;
    public function __construct($app)
    {
        $this->app = $app;
    }

    public function teszt(){
        $this->app->set("variableFromClass","This is a variable from my controllerClass");
    }

    public function string(){
        $this->app->set("variableFromClassString","This is a variable from my controllerClass as string");
    }
    public function json($id,$id2){
        echo $id;
        echo $id2;
        return ["status"=>1];
    }
    public function cli(...$args){
        print_r($args);
    }
}