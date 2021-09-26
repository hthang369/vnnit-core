<?php

namespace Vnnit\Core\Support;

use Vnnit\Core\Http\Controllers\BaseController;

class Factory
{
    /**
     * @var BaseController
     */
    private $controllerInstance;

    public function __construct($controllerInstance) {
        $this->controllerInstance = $controllerInstance;
    }

    public function makeRepository($nameClass, $params = [], $alias = '') {
        // $namespace = $this->getRepositoryPath($name);
        $instance = app($nameClass, $params);
        if (!$alias) {
            $alias = class_basename($nameClass);
        }
        $alias = lcfirst($alias);

        $this->controllerInstance->setProperty($instance, $alias);
        return $instance;
    }

    public function makeService($nameClass, $params = [], $alias = '') {
        // $namespace = $this->getServicePath($name);
        $instance = app($nameClass, $params);
        if (!$alias) {
            $alias = class_basename($nameClass);
        }
        $alias = lcfirst($alias);

        $this->controllerInstance->setProperty($instance, $alias);
        return $instance;
    }
}
