<?php

namespace Pachel\Light\src;

use Pachel\Light\src\Models\codeRunner;
use Pachel\Light\src\Models\Route;
use Pachel\Light\src\Parents\CallBack;
use Pachel\Light\src\Traits\CallProtected;

class Auth
{
    public const
        POLICY_ALLOW = 'allow',
        POLICY_DENY = 'deny'
    ;
    private $defaultPolicy = self::POLICY_ALLOW;
    private $paths = [

    ];
    /**
     * @var codeRunner $authMethod
     */
    private $authMethod;
    use CallProtected;
    public function policy()
    {
        return new Policy($this);
    }
    public function allow($path)
    {
        $this->paths[] = $path;
    }
    public function deny($path)
    {
        $this->paths[] = $path;
    }
    public function AuthMethod($method)
    {
        $this->authMethod = new codeRunner($method);
    }
    protected function policyCallback($policy)
    {
        $this->defaultPolicy = $policy;
    }
    private function inArray($path)
    {
        if(substr($path,strlen($path)-1,1) == '/'){
            $path = substr($path,0,strlen($path)-1);
        }
        foreach ($this->paths as $search) {
            $text = Light::$Routing->getTextToRegex($search);
            if(preg_match("/^".$text."$/", $path)) {
                return true;
            }
            else{
                return false;
            }
        }
        return false;
    }
    public function authenticate($path)
    {
        if(empty($this->authMethod)){
            return true;
        }
        if($this->defaultPolicy == self::POLICY_ALLOW){
            if(empty($this->paths))
                return true;
            if(!$this->inArray($path)){
                return true;
            }
        }
        if($this->defaultPolicy == self::POLICY_DENY){
            if($this->inArray($path)){
                return true;
            }
        }
        $this->authMethod->addVariables([$path]);
        return $this->authMethod->run();
    }

    /**
     * @param int[] $list
     * @return bool
     */
    public function authFromUrlArray($list)
    {

        foreach ($list as $item) {
            if($this->authenticate(Light::$Routing->getRoute($item)->getPath())){
                return true;
            }
        }
        return false;
    }

}
class Policy extends CallBack
{
    public function allow()
    {
        $this->ParentClass->policyCallback(Auth::POLICY_ALLOW);
    }
    public function deny(){

        $this->ParentClass->policyCallback(Auth::POLICY_DENY);
    }
}