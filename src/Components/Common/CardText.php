<?php

namespace Vnnit\Core\Components\Common;

use Vnnit\Core\Components\Component;

class CardText extends Component
{
    public $tag;
    public $text;

    /**
     * The component alias name.
     *
     * @var string
     */
    public $componentName = 'card-text';

    public function __construct(
        $text = '',
        $tag = 'div'
    )
    {
        $this->tag = $tag;
        $this->text = $text;
    }
}
