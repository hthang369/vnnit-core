<?php

namespace Vnnit\Core\Components\Common;

use Vnnit\Core\Components\Component;
use Vnnit\Core\Helpers\Classes;

class Card extends Component
{
    public $title;
    public $header;
    public $footer;
    public $noBody;
    public $bodyAttr;
    public $attrs;
    public $prefix;
    public $imgSrc;
    public $imgTop;
    public $imgBottom;
    public $darkMode;

    /**
     * The component alias name.
     *
     * @var string
     */
    public $componentName = 'card';

    public function __construct(
        $title = '',
        $header = '',
        $footer = '',
        $image = '',
        $class = '',
        $noBody = false,
        $bodyClass = '',
        $imgSrc = null,
        $imgTop = true,
        $imgBottom = false,
        $darkMode = false
    )
    {
        $this->prefix = config('vnnit-core.prefix');
        $this->header = $header;
        $this->title = $title;
        $this->footer = $footer;
        $this->noBody = $noBody;
        $this->imgSrc = $imgSrc;
        $this->imgTop = $imgTop;
        $this->imgBottom = $imgBottom;
        $this->darkMode = $darkMode;
        $this->bodyAttr = ['class' => Classes::get(['card-body', $bodyClass])];
        $this->attrs = ['class' => Classes::get(['card', $class])];
    }
}
