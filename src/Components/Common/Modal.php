<?php

namespace Vnnit\Core\Components\Common;

use Vnnit\Core\Components\Component;
use Vnnit\Core\Helpers\Classes;

class Modal extends Component
{
    public $childs;
    public $attrs;
    public $title;
    public $body;
    public $footer;
    public $dialog;

    public function __construct(
        $title = '',
        $body = '',
        $footer = '',
        $class = '',
        $scrollable = false,
        $centered = false,
        $size = '',
        $id = ''
    )
    {
        $this->title = $title ?? '';
        $this->body = $body ?? '';
        $this->footer = $footer ?? '';
        $this->scrollable = $scrollable ?? false;
        $this->centered = $centered ?? false;
        $this->size = $size ?? '';
        $this->attrs = [
            'id' => $id ?? '',
        ];
        $this->attrs['class'] = Classes::get([
            'modal',
            'class' => $class ?? 'fade',
        ]);
        $this->dialog['class'] = Classes::get([
            'modal-dialog',
            $this->scrollable === true ? 'modal-dialog-scrollable' : '',
            $this->centered === true ? 'modal-dialog-centered' : '',
            !empty($this->size) ? 'modal-' . $this->size : '',
        ]);
        $this->attrs = \array_filter($this->attrs);
    }
}
