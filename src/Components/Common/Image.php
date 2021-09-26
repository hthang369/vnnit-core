<?php

namespace Vnnit\Core\Components\Common;

use Illuminate\Support\Facades\File;
use Jenssegers\Agent\Agent;
use Intervention\Image\ImageManagerStatic as Resize;
use Vnnit\Core\Components\Component;

class Image extends Component
{
    public $width;
    public $fit;
    public $src;
    public $lazyload;
    public $attrs;
    public $attrs2;

    /**
     * The component alias name.
     *
     * @var string
     */
    public $componentName = 'image';

    public function __construct(
        $width = [],
        $height = [],
        $fit = '',
        $src = '',
        $alt = '',
        $id = '',
        $lazyload = true,
        $class = ''
    ) {
        $this->width = $width ?? '';
        $this->height = $height ?? '';
        $this->fit = $fit ?? '';
        $this->lazyload = $lazyload ?? true;
        $this->attrs = [
            'class' => $class ?? false,
            'src' => $src ?? '',
            'id' => $id ?? '',
            'alt' => $alt ?? '',
        ];
        if (!is_array($this->width))
            $this->attrs = array_merge($this->attrs, ['width' => $this->width]);
        if (!is_array($this->height))
            $this->attrs = array_merge($this->attrs, ['height' => $this->height]);
        $this->attrs = \array_filter($this->attrs);
    }

    private function getSizes()
    {
        $agent = new Agent();
        $sizes = [
            'width' => '',
            'height' => '',
        ];
        if ($agent->isMobile() && !$agent->isTablet()) {
            if (isset($this->width[1])) {
                $sizes['width'] = $this->width[1];
                $this->attrs['width'] = $sizes['width'];
            }
            if (isset($this->height[1])) {
                $sizes['height'] = $this->height[1];
                $this->attrs['height'] = $sizes['height'];
            }
        } else {
            if (is_array($this->width) && !empty($this->width[0])) {
                $sizes['width'] = $this->width[0];
                $this->attrs['width'] = $sizes['width'];
            }
            if (is_array($this->height) && !empty($this->height[0])) {
                $sizes['height'] = $this->height[0];
                $this->attrs['height'] = $sizes['height'];
            }
        }

        return $sizes;
    }

    private function lazyloadSrc($src)
    {
        return $this->lazyload === true ? $src : '';
    }

    private function lazyloadActive()
    {
        return $this->lazyload === true ? 'true' : '';
    }

    private function getFileName()
    {
        $sizes = $this->getSizes();
        $folder = $this->getPathInfo();
        $fileNameSize = '_' . $sizes['width'] . 'x' . $sizes['height'];

        return $folder['filename'] . $fileNameSize . '.' . $folder['extension'];
    }

    private function getImageResizedFolder()
    {
        $folder = $this->getPathInfo();

        $hasSlash = substr($folder['dirname'], 0, 1) === '/' ? true : false;
        $folder['dirname'] = $hasSlash ? $folder['dirname'] : '/' . $folder['dirname'];

        return public_path('resize' . $folder['dirname']);
    }

    private function getResizedImageSrc()
    {
        $folder = $this->getPathInfo();

        if ($this->isExtern() === true) {
            return $this->attrs['src'];
        } else {
            if ($this->isResize() === false) {
                return url($this->attrs['src']);
            } else {
                return url($folder['dirname'] . '/resize/' . $this->getFileName());
            }
        }
    }

    private function getPathInfo()
    {
        return pathinfo($this->attrs['src']);
    }

    private function isExtern()
    {
        if (
            isset($this->getPathInfo()['dirname']) &&
            \strpos($this->getPathInfo()['dirname'], 'http') === false &&
            \strpos($this->getPathInfo()['dirname'], 'www') === false &&
            \strpos($this->getPathInfo()['dirname'], '//') === false
        ) {
            return false;
        } else {
            return true;
        }
    }

    private function isResize()
    {
        if (
            isset($this->width[0]) ||
            isset($this->width[1]) ||
            isset($this->height[0]) ||
            isset($this->height[1])
        ) {
            return true;
        } else {
            return false;
        }
    }

    private function getPlaceholder()
    {
        return 'https://via.placeholder.com/150';
    }

    private function buildResizedImage()
    {
        if (File::exists($this->attrs['src']) === false) {
            $this->attrs['src'] = $this->getPlaceholder();
        }

        $sizes = $this->getSizes();
        $imageResizedPath = $this->getImageResizedFolder() . '/' . $this->getFileName();
        if (!File::exists($imageResizedPath)) {
            $image_resize = Resize::make(public_path($this->attrs['src']));
            File::makeDirectory(
                $this->getImageResizedFolder(),
                $mode = 0777,
                true,
                true
            );
            $image_resize->resize($sizes['width'], $sizes['height'],
                function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });
            $image_resize->save($imageResizedPath);
        }
    }

    public function render()
    {
        if ($this->isExtern() === false) {
            $this->buildResizedImage();
        }
        $this->getSizes();
        // $this->attrs['data-lazy'] = $this->lazyloadActive();
        if ($this->lazyload)
            $this->attrs['loading'] = 'lazy';
        // $this->attrs['data-srcset'] = $this->lazyloadSrc(
        //     $this->getResizedImageSrc()
        // );

        // if ($this->lazyload === true) {
        //     $this->attrs['src'] = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNkYAAAAAYAAjCB0C8AAAAASUVORK5CYII=';
        // } else {
            $this->attrs['src'] = $this->getResizedImageSrc();
        // }

        $this->attrs = \array_filter($this->attrs);

        return parent::render();
    }
}
