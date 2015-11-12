<?php

namespace ntentan\utils;

trait DependencyInjector
{
    protected $loadedDependencies = [];
    
    private static $resolver;
    
    public function getDependency($dependency)
    {
        if(isset($this->loadedDependencies[$dependency])) {
            return $this->loadedDependencies[$dependency];
        }
    }
    
    protected function loadDependency($dependency, $params = null)
    {
        $resolver = self::$resolver;
        $className = $resolver($dependency);
        $dependencyInstance = new $className($params);
        $this->loadedDependencies[Text::camelize($dependency)] = $dependencyInstance;
        return $dependencyInstance;
    }
    
    public static function setDependencyResolver($resolver)
    {
        self::$resolver = $resolver;
    }
}
