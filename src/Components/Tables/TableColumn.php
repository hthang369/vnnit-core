<?php

namespace Vnnit\Core\Components\Tables;

use Vnnit\Core\Components\Component;
use Vnnit\Core\Grids\DataColumn;

class TableColumn extends Component
{
    /**
     * The component alias name.
     *
     * @var string
     */
    public $componentName = 'table-column';

    public $tag;
    public $field;
    public $cellData;
    public $isRowHeader;
    public $tableId;
    public $except;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(DataColumn $field, $data = null, $isHeader = false, $tableId = '', $except = null)
    {
        $this->field = $field;
        $this->cellData = $data;
        $this->isRowHeader = $isHeader;
        $this->tableId = $tableId;
        $this->except = $except;
        $this->tag = $isHeader ? 'th' : 'td';
    }
}
