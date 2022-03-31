<?php
namespace Vnnit\Core\Traits\Common;

trait ReflectiorTrait
{
    public function callMethodClass($class, $methodName, ...$args)
    {
        $method = new \ReflectionMethod($class, $methodName);
        if ($method) {
            $method->setAccessible(true);
            return $method->invoke($class, ...$args);
        }
    }
}
