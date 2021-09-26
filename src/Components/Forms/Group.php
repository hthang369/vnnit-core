<?php

namespace Vnnit\Core\Components\Forms;

use Vnnit\Core\Components\Component;

class Group extends Component
{
    /**
     * The component alias name.
     *
     * @var string
     */
    public $componentName = 'form-group';

    public $name;
    public $label;
    public $inline;
    public $labelClass;
    public $labelFor;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        string $name = '',
        string $label = '',
        bool $inline = false,
        bool $showErrors = true,
        string $labelClass = '',
        string $labelFor = '')
    {
        $this->name = $name;
        $this->label = $label;
        $this->inline = $inline;
        $this->labelClass = $labelClass;
        $this->labelFor = $labelFor;
    }
}
