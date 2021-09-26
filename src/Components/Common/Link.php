<?php

namespace Vnnit\Core\Components\Common;

use Vnnit\Core\Components\Component;
use Vnnit\Core\Helpers\Classes;

class Link extends Component
{
    public $text;
    public $trim;
    public $attrs;
    public $collapse;

    public function __construct(
        $href = '',
        $title = '',
        $trim = 0,
        $text = '',
        $collapse = [],
        $target = '',
        $class = ''
    )
    {
        $this->text = $text ?? '';
        $this->trim = $trim ?? 0;
        $this->collapse = $collapse ?? [];
        $this->attrs = [
            'class' => $class ?? '',
            'href' => $href ?? '',
            'title' => $title ?? '',
            'target' => $target ?? '',
        ];
        if (isset($this->collapse['id'])) {
            $this->attrs['data-toggle'] =  'collapse';
            $this->attrs['data-target'] =  '#collapse-' . $this->collapse['id'];
            $this->attrs['href'] =  '#';
        }
        $this->attrs['class'] = Classes::get([
            $this->all['class'] ?? '',
            $this->attrs['class']
        ]);
        $this->attrs = \array_filter($this->attrs);
    }
}
