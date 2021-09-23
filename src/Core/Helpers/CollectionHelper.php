<?php

namespace Vnnit\Core\Helpers;

class CollectionHelper
{
    public static function makeMultiFieldsComparer()
    {
        return function ($criteria) {
            $comparer = function ($first, $second) use ($criteria) {
                foreach ($criteria as $key => $orderType) {
                    if (!array_key_exists($key, $first)) {
                        continue;
                    }

                    $orderType = strtolower($orderType);
                    if ($first[$key] < $second[$key]) {
                        return $orderType === "asc" ? -1 : 1;
                    } else if ($first[$key] > $second[$key]) {
                        return $orderType === "asc" ? 1 : -1;
                    }
                }
                return 0;
            };
            return $comparer;
        };
    }

    public static function getProtectedProperty($obj, $prop) {
        $reflection = new \ReflectionClass($obj);
        $property = $reflection->getProperty($prop);
        $property->setAccessible(true);
        return $property->getValue($obj);
    }

    public static function getConstantsByClass($class_name)
    {
        $reflection = new \ReflectionClass($class_name);
        return $reflection->getConstants();
    }
}
