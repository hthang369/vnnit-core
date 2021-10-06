<?php

namespace Vnnit\Core\Components\Common;

use Vnnit\Core\Components\Component;

class SectionTitle extends Component
{
    /**
    * @var string
    */
    public $title;

    /**
     * The component alias name.
     *
     * @var string
     */
    public $componentName = 'section-title';

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($title)
    {
        $this->title = $title;
    }
}
