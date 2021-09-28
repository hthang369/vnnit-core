<?php

namespace Vnnit\Core\Components\Common;

use Vnnit\Core\Components\Component;
use Vnnit\Core\Helpers\Classes;

class Breadcrumb extends Component
{
    public $attrs;
    public $pages;
    public $currentPage;

    public function __construct(
        $class = '',
        $pages = []
    )
    {
        $this->pages = $pages ?? [];
        $this->attrs = [
            'class' => $class ?? '',
        ];
        $this->attrs['class'] = Classes::get([
            'breadcrumb',
            $this->attrs['class']
        ]);
        $this->attrs = \array_filter($this->attrs);
    }
}
