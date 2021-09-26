<?php

namespace Vnnit\Core\Components\Forms;

use Vnnit\Core\Components\Component;

class Checkbox extends Component
{
    /**
     * The component alias name.
     *
     * @var string
     */
    public $componentName = 'form-checkbox';

    public $name;
    public $label;
    public $chkGroupCLass;
    public $class;
    public $labelAttr;
    public $value;
    public $checked;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($name, $label, $custom = false, $checked = false)
    {
        $this->name = $name;
        $this->label = $label;
        $this->chkGroupCLass = $custom ? join(' ', ['custom-control', 'custom-checkbox']) : 'form-check';
        $this->class = $custom ? ['custom-control-input'] : ['form-check-input'];
        $this->labelAttr = ['class' => $custom ? 'custom-control-label' : 'form-check-label'];
        $this->value = 1;
        $this->checked = $checked;
    }
}
