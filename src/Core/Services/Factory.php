<?php
/**
 * Created by PhpStorm.
 * User: ngoc_luan
 * Date: 7/17/2020
 * Time: 10:49 AM
 */

namespace Vnnit\Core\Services;


class Factory
{
    private $repositoryBasePath;
    private $entityBasePath;
    private $serviceBasePath;
    private $controllerInstance;

    public function __construct($namespace, $controllerInstance)
    {
        $this->makeDirPath($namespace);
        $this->controllerInstance = $controllerInstance;
    }

    private function makeDirPath($namespace)
    {
        $moduleName = $this->getModuleName($namespace);
        $this->repositoryBasePath = 'Vnnit\\' . $moduleName . '\Repositories\\';
        $this->serviceBasePath = 'Vnnit\\' . $moduleName . '\Services\\';
    }

    private function getModuleName($namespace)
    {

        $arr = explode('\\', $namespace);
        return $arr[1];

    }

    private function getRepositoryPath($name)
    {
        return $this->repositoryBasePath . $name;
    }

    private function getServicePath($name)
    {
        return $this->serviceBasePath . $name;
    }

    public function makeRepository($name, $params = [], $alias = '')
    {
        $namespace = $this->getRepositoryPath($name);
        $instance = new $namespace($params);
        if (!$alias) {
            $alias = $name;
        }
        $alias = lcfirst($alias);

        $this->controllerInstance->setProperty($instance, $alias);
        return $instance;
    }

    public function makeService($name, $params = [], $alias = '')
    {
        $namespace = $this->getServicePath($name);
        $instance = new $namespace($params);
        if (!$alias) {
            $alias = $name;
        }
        $alias = lcfirst($alias);

        $this->controllerInstance->setProperty($instance, $alias);
        return $instance;
    }
}
