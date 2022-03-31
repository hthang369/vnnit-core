<?php

namespace Vnnit\Core\Components\Common;

use Vnnit\Core\Components\Component;

class Number extends Component
{
    public $name;
    public $value;
    public $options;
    public $attrs;
    /**
     * The component alias name.
     *
     * @var string
     */
    public $componentName = 'number';

    public function __construct(
        $name,
        $min = null,
        $max = null,
        $value = null,
        bool $readonly = true,
        $fieldClass = null
    )
    {
        $this->name = $name;
        $this->value = $value;
        $this->options = [];
        if (!blank($min) && is_numeric($min)) data_set($this->options, 'min', $min);
        if (!blank($max) && is_numeric($max)) data_set($this->options, 'max', $max);
        if ($readonly) data_set($this->options, 'readonly', $readonly);
        if (!blank($fieldClass)) data_set($this->options, 'class', $fieldClass);
        $this->attrs = ['class' => 'number-input'];
    }
}
