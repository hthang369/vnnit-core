<?php

namespace Vnnit\Core\Grids;

use Vnnit\Core\Traits\Grids\CallableData;

class LookupData
{
    use CallableData;

    protected $privateField = ['items'];

    private $items = [];
    private $dataSource = null;
    private $displayExpr = '';
    private $valueExpr = '';

    public function fill($data)
    {
        foreach($data as $field => $value) {
            $this->{$field} = $value;
        }
        $this->items = collect($this->dataSource)->pluck($this->displayExpr, $this->valueExpr);
    }

    public static function make($data)
    {
        $object = new self;
        $object->fill($data);
        return $object;
    }
}
