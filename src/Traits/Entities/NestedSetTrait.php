<?php

namespace Vnnit\Core\Traits\Entities;

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
        Collection::macro('toList', function($name, $key = null) {
            $menus = $this->toTree();
            return $menus->map(function($menu) use($name, $key) {
                $data = array_pluck([$menu], $name, $key);
                if ($menu->children->count() > 0) {
                    $data[] = array_pluck($menu->children, $name, $key);
                }
                return $data;
            })->all();
        });
    }

}
