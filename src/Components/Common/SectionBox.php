<?php

namespace Vnnit\Core\Components\Common;

use Vnnit\Core\Components\Component;

class SectionBox extends Component
{
    /**
    * @var string
    */
    public $title;

    /**
    * @var string
    */
    public $wrapper;

    /**
     * The component alias name.
     *
     * @var string
     */
    public $componentName = 'section-box';

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($title, $wrapper = null)
    {
        $this->title = $title;
        $this->wrapper = $wrapper ?? 'container';
    }
}
