<?php
namespace Vnnit\Core\Traits\Common;

trait MakeObjectInstance
{
    /**
     * @param null $className
     *
     * @return mixin
     * @throws RepositoryException
     */
    public function makeObject($className)
    {
        if (!is_null($className)) {
            return is_string($className) ? resolve($className) : $className;
        }

        return null;
    }
}
