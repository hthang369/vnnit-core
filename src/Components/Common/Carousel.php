<?php

namespace Vnnit\Core\Components\Common;

use Vnnit\Core\Components\Component;

class Carousel extends Component
{
    public $attrs;
    public $items;
    public $indicators;
    public $control;

    public function __construct(
        $items = [],
        $indicators = false,
        $control = false,
        $class = '',
        $id = ''
    )
    {
        $this->items = $items ?? [];
        $this->indicators = $indicators ?? false;
        $this->control = $control ?? false;
        $this->attrs = [
            'class' => 'carousel slide ' . ($class ?? ''),
            'id' => $id ?? '',
        ];
        $this->attrs = \array_filter($this->attrs);
    }
}
