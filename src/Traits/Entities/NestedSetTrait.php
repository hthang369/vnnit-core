<?php

namespace Vnnit\Core\Traits\Entities;

use Illuminate\Support\Collection as SupportCollection;
use Kalnoy\Nestedset\Collection;
use Kalnoy\Nestedset\NodeTrait;
use Kalnoy\Nestedset\NestedSet;

/*
 * A trait to handle use full text search
 */

trait NestedSetTrait
{
    use NodeTrait;

    protected $prefixColumn = '';

    public function setPrefixColumn($value)
    {
        $this->prefixColumn = $value;
    }

    /**
     * Get the lft key name.
     *
     * @return  string
     */
    public function getLftName()
    {
        return $this->prefixColumn . NestedSet::LFT;
    }

    /**
     * Get the rgt key name.
     *
     * @return  string
     */
    public function getRgtName()
    {
        return $this->prefixColumn . NestedSet::RGT;
    }

    /**
     * {@inheritdoc}
     */
    public function newCollection(array $models = array())
    {
        $this->registerExtensionFunction();
        return new Collection($models);
    }

    private function registerExtensionFunction()
    {
        Collection::macro('toPluck', function($name, $key = null) {
            $menus = $this->toTree();
            return $menus->map(function($menu) use($name, $key) {
                return $this->nestedPluckItem($menu, $name, $key);
            })->all();
        });

        Collection::macro('toList', function($columns = []) {
            $menus = $this->toTree()->toArray();
            if (count($columns) > 0) {
                return $this->nestedListItem($menus, $columns);
            }
            return $menus;
        });

        Collection::macro('nestedPluckItem', function($menu, $name, $key = null) {
            if ($menu->children->count() > 0) {
                if ($menu->children->has('children')) {
                    return $this->nestedPluckItem($menu->children, $name, $key);
                }
                return array_pluck($menu->children, $name, $key);
            }
            return array_pluck([$menu], $name, $key);
        });

        Collection::macro('nestedListItem', function($menus, $columns = []) {
            $data = array_map(function($item) use($columns) {
                $menu = array_only($item, $columns);
                $menu = array_map(function($menuItem) use($columns) {
                    if (is_array($menuItem)) {
                        return $this->nestedListItem($menuItem, $columns);
                    }
                    return $menuItem;
                }, $menu);
                return $menu;
            }, $menus);

            return $data;
        });
    }
}
