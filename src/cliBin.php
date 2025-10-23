<?php

namespace Pachel\Light\src;

class cliBin
{
    /**
     * @var Light $app
     */
    private $app;
    private $root;

    public function __construct($app)
    {
        $this->app = $app;
        if(defined("LFW_DEV_MODE") && LFW_DEV_MODE){
            $this->root = __DIR__ . "/../example/";
        }
        else {
            $this->root = __DIR__ . "/../../../../";
        }
    }

    private function copyFiles($from = null)
    {
        $ToDir = $this->root.$from;
        $FromDir = __DIR__."/../bin/demo/".$from;
        echo $ToDir."\n";
        echo $FromDir."\n";
        $files = scandir($FromDir);
        foreach ($files AS $file){
            if($file == "." || $file == ".."){
                continue;
            }

            if(is_file($FromDir.$file)){
                if(file_exists($ToDir.$file)){
                    do{
                        echo "A fájl már létezik: ".$ToDir.$file."\nHa felül szeretnéd írni? (y/n): ";
                        $value = readline();
                    }while(!$this->yesOrNo($value));
                    if(strtoupper($value) == "Y") {
                        echo "Törlés: ".$file."\n";
                        unlink($ToDir . $file);
                        echo "Másolás: ".$file."\n";
                        copy($FromDir . $file, $ToDir . $file);
                    }
                }
                else{
                    echo "Másolás: ".$file."\n";
                    copy($FromDir . $file, $ToDir . $file);
                }
            }
            else{
                if(!is_dir($ToDir . $file)){
                    mkdir($ToDir . $file);
                }
                $this->copyFiles($file."/");
            }
        }

    }
    private function yesOrNo($value){
        if(in_array(strtoupper($value),["Y","N"])){
            return true;
        }
        return false;
    }

    public function Install()
    {
        $this->copyFiles();
    }
}