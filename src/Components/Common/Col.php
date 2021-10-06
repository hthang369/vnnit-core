<?php

namespace Vnnit\Core\Components\Common;

use Vnnit\Core\Components\Component;

class Col extends Component
{
    public $class;
    /**
     * The component alias name.
     *
     * @var string
     */
    public $componentName = 'col';

    public function __construct($size = '', $device = '')
    {
        $classSize = !blank($size) ? "-{$size}" : '';
        $classDevice = !blank($device) ? "-{$device}" : '';
        $this->class = "col{$classDevice}{$classSize}";
    }
}
