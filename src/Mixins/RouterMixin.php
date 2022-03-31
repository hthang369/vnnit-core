<?php
namespace Vnnit\Core\Mixins;

class RouterMixin
{
    public function sortRoute()
    {
        return function(...$value) {
            $routes = $this->getRoutes();
            $reflector = new \ReflectionClass($routes);
            $property = $reflector->getProperty('routes');
            $property->setAccessible(true);
            $object = $property->getValue($routes);
            data_set($object, 'GET', collect(data_get($object, 'GET'))->sort()->all());
            data_set($object, 'HEAD', collect(data_get($object, 'HEAD'))->sort()->all());
            $property->setValue($routes, $object);
        };
    }
}
