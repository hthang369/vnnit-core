<?php

namespace Vnnit\Core\Helpers;

use AustinHeap\Database\Encryption\Traits\HasEncryptedAttributes;
use HaydenPierce\ClassFinder\ClassFinder;
use Vnnit\Core\Entities\BaseModel;

class Encrypt
{
    public static function findEncryptedModelsInNamespace($namespace)
    {
        $trait = HasEncryptedAttributes::class;

        $definedClasses = ClassFinder::getClassesInNamespace($namespace, ClassFinder::RECURSIVE_MODE);

        // select only classes that use trait $trait
        return array_filter(
            $definedClasses,
            function ($className) use ($trait) {
                $traits = class_uses($className);
                return isset($traits[$trait]);
            }
        );
    }

    public static function getEncryptedFields(string $model)
    {
        $instance = new $model;
        if (!($instance instanceof BaseModel)) {
            [null, []];
        }

        return [
            $instance->getTable(),
            $instance->getEncryptedFields()
        ];
    }
}
