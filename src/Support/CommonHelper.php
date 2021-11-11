<?php

namespace Vnnit\Core\Support;

use Collective\Html\HtmlFacade as Html;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Request;
use Nwidart\Menus\Facades\Menu;

class CommonHelper
{
    public function getSectionCode()
    {
        if (\is_null(Request::route())) return '';
        $routeName = explode('.', Request::route()->getName());
        return trim(head($routeName));
    }

    public function callApi($method, $url, $params = [])
    {
        $fullUrl = config('laka.api_address')."{$url}";
        $response = Http::withHeaders([
            'Accept' => 'application/json',
            // 'Content-Type' => 'application/x-www-form-urlencoded',
            'token' => config('laka.api_token')
        ])->{$method}($fullUrl, $params);

        if ($response->ok())
            return $response->collect();

        return $response->body();
    }

    public function getViewName($name)
    {
        $prefix = config('vnnit-core.prefix');
        return "{$prefix}::components.{$name}";
    }

    public function renderMenus($dataTree, $menuName, $menuStyle = null, $dropdown = true, $callback = null)
    {
        Menu::create($menuName, function($menu) use($dataTree, $menuStyle, $dropdown, $callback) {
            if (!is_null($menuStyle))
                $menu->style($menuStyle);
            $this->renderElementMenu($menu, $dataTree, 'nav-link', $dropdown, $callback);
        });
        return Menu::get($menuName);
    }

    private function renderElementMenu(&$menu, $dataTree, $class = 'nav-link', $dropdown = true, $callback = null)
    {
        $dropdownClass = $dropdown ? 'dropdown-item' : $class;
        $dataTree->each(function($item) use(&$menu, $class, $dropdownClass, $dropdown, $callback) {
            if (data_get($item, 'visiable', true)) {
                $childrens = data_get($item, 'children');
                if ($childrens && $childrens->count() > 0) {
                    $options = [];
                    if (is_callable($callback)) {
                        $options = with($item, $callback);
                    }
                    $menu->dropdown(data_get($item, 'menu_title'), function ($subMenu) use ($childrens, $dropdownClass, $dropdown, $callback) {
                        $this->renderElementMenu($subMenu, $childrens, $dropdownClass, $dropdown, $callback);
                    }, $options);
                } else {
                    $options = [];
                    if (is_callable($callback)) {
                        $options = with($item, $callback);
                    }
                    $menu->url(data_get($item, 'menu_link'), data_get($item, 'menu_title'),
                        array_merge([
                            'class' => $class,
                            'icon' => data_get($item, 'menu_icon'),
                            'active' => data_get($item, 'actived', false)
                        ], $options));
                }
            }
        });
    }

    public function getPictureImageFormPath($path, $width = null, $height = null, $altName = '')
    {
        $pathImage = asset($path);
        if (!file_exists($pathImage)) {
            $pathImage = asset(config('filesystems.disks.public.path_image').$path);
        }
        if (blank($altName)) {
            $altName = pathinfo($pathImage, PATHINFO_FILENAME);
        }
        $attrs = array_filter(['class' => 'img-fluid', 'width' => $width, 'height' => $height]);
        return Html::image($pathImage, $altName, $attrs)->toHtml();
    }
}
