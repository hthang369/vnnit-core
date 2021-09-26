<?php

namespace Vnnit\Core\Components\Forms;

use Vnnit\Core\Components\Component;
use Vnnit\Core\Helpers\Classes;

class Datepicker extends Component
{
    public $name;
    public $class;
    public $dateFormat = 'yyyy-mm-dd';
    public $value;

    /**
     * The component alias name.
     *
     * @var string
     */
    public $componentName = 'datepicker';

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($name, $dateFormat = 'yyyy-mm-dd', $size = null, $value = null)
    {
        $this->name = $name;
        $this->class = Classes::get([
            'form-control',
            !$size ? '' : sprintf('form-control-%s', $size)
        ]);
        $this->dateFormat = $dateFormat;
        $this->value = $value;
    }
}
