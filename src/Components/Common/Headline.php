<?php

namespace Vnnit\Core\Components\Common;

use Vnnit\Core\Components\Component;

class Headline extends Component
{
    public $text;
    public $tag;
    public $link;
    public $trim;
    public $attrs;

    public function __construct(
        $link = [],
        $text = '',
        $trim = 0,
        $tag = '',
        $class = ''
    )
    {
        $this->link = $link ?? [];
        $this->text = $text ?? '';
        $this->tag = $tag ?? 'h2';
        $this->trim = $trim ?? 0;
        $this->attrs = [
            'class' => $class ?? '',
        ];
        $this->attrs = \array_filter($this->attrs);
    }
}
