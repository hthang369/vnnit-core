<?php

namespace Vnnit\Core\Grids;

use Vnnit\Core\Traits\Grids\CallableData;

class DataColumn
{
    use CallableData;

    private $key;
    private $label;
    private $class;
    private $dataType = 'string';
    private $sortable = false;
    private $filtering = false;
    private $variant;
    private $visible = true;
    private $headerTitle;
    private $tdClass;
    private $thClass;
    private $thStyle;
    private $tdAttr = [];
    private $isRowHeader = false;
    private $stickyColumn = false;
    private $cell;
    /**
     * @return LookupData
     */
    private $lookup = null;

    public static function make($data)
    {
        $object = new self;
        $object->fill($data);
        return $object;
    }
}
