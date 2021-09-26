<?php

namespace Vnnit\Core\Components\Tables;

use Vnnit\Core\Components\Component;
use Vnnit\Core\Helpers\DataColumn;

class TableFilter extends Component
{
    /**
     * The component alias name.
     *
     * @var string
     */
    public $componentName = 'table-filter';

    public $field;
    public $type;

    private $filterList = [
        'string'    => 'text',
        'date'      => 'datepicker'
    ];

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(DataColumn $field)
    {
        $this->field = $field;
        $this->type = data_get($this->filterList, $field->dataType);
        if (!is_null($field->lookup->dataSource)) {
            $this->type = 'select';
        }
    }
}
