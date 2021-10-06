<?php

namespace Vnnit\Core\Traits\Grids;

trait CallableData
{
    public function __get($name)
    {
        return $this->{$name};
    }

    public function __set($name, $value)
    {
        if (count($this->privateField) > 0 && in_array($name, $this->privateField))
            throw new \Exception("$name is private property! Can not set value private property");

        $this->{$name} = $value;
    }

    public function fill($data)
    {
        foreach($data as $field => $value) {
            $this->{$field} = $value;
        }
    }
}
