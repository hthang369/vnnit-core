<?php

namespace Vnnit\Core\Components\Forms;

use Vnnit\Core\Components\Component;

class Form extends Component
{
    /**
     * The component alias name.
     *
     * @var string
     */
    public $componentName = 'form';

    public $route;
    public $method;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($route, $method = 'GET')
    {
        $this->route = $route;
        $this->method = $method;
    }
}
