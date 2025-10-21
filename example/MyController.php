<?php

class MyController
{
    public function teszt(){

    }
    public function json($id,$id2){
        echo $id;
        echo $id2;
        return ["status"=>1];
    }
}