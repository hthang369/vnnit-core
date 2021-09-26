<?php

namespace Vnnit\Core\Components\Common;

use Jenssegers\Agent\Agent;
use Vnnit\Core\Components\Component;

class Svg extends Component
{
    public $path;
    public $attrs;
    public $width;
    public $height;

    public function __construct(
        $path = '',
        $width = [],
        $height = [],
        $class = ''
    )
    {
        $this->path = $path ?? '';
        $this->width = $width ?? [16, 16];
        $this->height = $height ?? [16, 16];
        $this->attrs = [
            'class' => $class ?? '',
        ];
    }

    private function getSizes()
    {
        $agent = new Agent();
        $sizes = [
            'width' => '',
            'height' => '',
        ];
        if ($agent->isMobile() && !$agent->isTablet()) {
            if (!empty($this->width)) {
                $sizes['width'] = $this->width[1] ?? $this->width[0];
                $this->attrs['width'] = $sizes['width'];
            }
            if (!empty($this->height)) {
                $sizes['height'] = $this->height[1] ?? $this->height[0];
                $this->attrs['height'] = $sizes['height'];
            }
        } else {
            if (!empty($this->width[0])) {
                $sizes['width'] = $this->width[0];
                $this->attrs['width'] = $sizes['width'];
            }
            if (!empty($this->height[0])) {
                $sizes['height'] = $this->height[0];
                $this->attrs['height'] = $sizes['height'];
            }
        }

        return $sizes;
    }

    public function render()
    {
        $this->getSizes();

        if (\strpos($this->attrs['class'], 'bi ') !== false) {
            $this->attrs['viewBox'] = '0 0 16 16';
        }

        return parent::render();
    }
}
