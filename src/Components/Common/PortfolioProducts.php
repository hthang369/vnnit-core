<?php
namespace Vnnit\Core\Components\Common;

use Vnnit\Core\Components\Component;
use Vnnit\Core\Helpers\Classes;

class PortfolioProducts extends Component
{
    /**
     * The component alias name.
     *
     * @var string
     */
    public $componentName = 'portfolio-products';

    public $items;
    public $attrs;
    public $size;
    public $cols;

    public function __construct(
        $items = [],
        $class = '',
        $size = null,
        $cols = null
    ) {
        $this->items = $items;
        $this->attrs = [
            'class' => Classes::get([
                $class ?? '',
                'col px-2 mb-3'
            ])
        ];
        $this->size = $size ?? 'md';
        $this->cols = $cols ?? '4';
        $this->attrs = \array_filter($this->attrs);
    }
}
