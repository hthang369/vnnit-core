<?php

namespace Vnnit\Core\Components\Common;

use Vnnit\Core\Components\Component;
use Vnnit\Core\Helpers\Classes;

class Alert extends Component
{
    public $type;
    public $dismissible;
    public $message;
    public $attrs;

    /**
     * The component alias name.
     *
     * @var string
     */
    public $componentName = 'alert';

    public function __construct(
        $type = '',
        $message = '',
        $class = '',
        $dismissible = false
    )
    {
        $this->type = $type ?: '';
        $this->dismissible = $dismissible ?: false;
        $this->message = $message ?: '';
        $this->attrs = [
            'class' => $class ?: '',
        ];
        $this->attrs['class'] = Classes::get([
            $this->type ? 'alert alert-' . $this->type : '',
            $this->dismissible ? 'alert-dismissible fade show' : '',
            $this->attrs['class']
        ]);
        $this->attrs = \array_filter($this->attrs);
    }
}
