<?php

namespace ntentan\utils;

trait DependencyInjector
{
    private $injectedDependencies = [];
    
    public function __call($method, $args)
    {
        foreach($this->injectedDependencies as $depnendency) {
            if(method_exists($depnendency, $args)) {
                return call_user_func_array([$depnendency, $method], $args);
            }
        }
    }
}
