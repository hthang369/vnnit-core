<?php

namespace Vnnit\Core\Components\Common;

use Vnnit\Core\Components\Component;

class CardFooter extends Component
{
    public $text;

    /**
     * The component alias name.
     *
     * @var string
     */
    public $componentName = 'card-footer';

    public function __construct(
        $text = ''
    )
    {
        $this->text = $text;
    }
}
