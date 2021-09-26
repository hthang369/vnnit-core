<?php

namespace Vnnit\Core\Components\Common;

use Vnnit\Core\Components\Component;
use Vnnit\Core\Helpers\Classes;

class Toasts extends Component
{
    public $type;
    public $dismissible;
    public $message;
    public $title;
    public $attrs;
    public $headerClass;

    /**
     * The component alias name.
     *
     * @var string
     */
    public $componentName = 'alert';

    public function __construct(
        $type = '',
        $title = '',
        $message = '',
        $class = '',
        $variant = '',
        $dismissible = true,
        $delay = 0
    )
    {
        $this->type = $type ?: '';
        $this->title = $title ?: '';
        $this->dismissible = $dismissible ?: true;
        $this->message = $message ?: '';
        $this->attrs = [
            'class' => $class ?: '',
            'role' => 'alert',
            'aria-live' => 'assertive',
            'aria-atomic' => 'true',
        ];
        if ($delay > 0) {
            $this->attrs['data-delay'] = $delay;
        }
        $this->attrs['class'] = Classes::get([
            'toast',
            $this->attrs['class']
        ]);
        if (!blank($variant)) {
            $this->headerClass = Classes::get([
                "bg-{$variant}",
                'text-light'
            ]);
        }
        $this->attrs = \array_filter($this->attrs);
    }
}
