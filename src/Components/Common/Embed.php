<?php

namespace Vnnit\Core\Components\Common;

use Vnnit\Core\Components\Component;
use Vnnit\Core\Helpers\Classes;

class Embed extends Component
{
    public $src;
    public $format;
    public $options;
    public $attrs;

    public function __construct(
        $src = '',
        $options = '',
        $format = '',
        $class = ''
    )
    {
        $this->src = $src ?? '';
        $this->options = $options ?? 'allowfullscreen';
        $this->format = $format ?? '16by9';
        $this->attrs = [
            'class' => $class ?? '',
        ];
        $this->attrs['class'] = Classes::get([
            'embed-responsive embed-responsive-' . $this->format,
            $this->attrs['class'],
        ]);
        $this->attrs = \array_filter($this->attrs);
    }
}
