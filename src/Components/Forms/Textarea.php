<?php

namespace Vnnit\Core\Components\Forms;

use Vnnit\Core\Components\Component;
use Vnnit\Core\Helpers\Classes;

class Textarea extends Component
{
    /**
     * The component alias name.
     *
     * @var string
     */
    public $componentName = 'form-textarea';

    public $name;
    public $class;
    public $help;
    public $groupClass;
    public $value;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        string $name = '',
        string $class = '',
        $help = '',
        $groupClass = '',
        $size = null,
        $value = null
    )
    {
        $this->name = $name;
        $classSize = $size ? sprintf('form-control-%s', $size) : '';
        $this->class = Classes::get([$class, $classSize]);
        $this->help = $help;
        $this->groupClass = $groupClass;
        $this->value = $value;
    }
}
