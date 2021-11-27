<?php

namespace Vnnit\Core\Plugins\Nestedset;

use Illuminate\Database\Eloquent\Collection as BaseCollection;
use Illuminate\Database\Eloquent\Model;

class Collection extends BaseCollection
{
    /**
     * Fill `parent` and `children` relationships for every node in the collection.
     *
     * This will overwrite any previously set relations.
     *
     * @return $this
     */
    public function linkNodes()
    {
        if ($this->isEmpty()) return $this;

        $groupedNodes = $this->groupBy($this->first()->getParentIdName());

        /** @var NodeTrait|Model $node */
        foreach ($this->items as $node) {
            if ( ! $node->getParentId()) {
                $node->setRelation('parent', null);
            }

            $children = $groupedNodes->get($node->getKey(), [ ]);

            /** @var Model|NodeTrait $child */
            foreach ($children as $child) {
                $child->setRelation('parent', $node);
            }

            $node->setRelation('children', BaseCollection::make($children));
        }

        return $this;
    }

    /**
     * Build a tree from a list of nodes. Each item will have set children relation.
     *
     * To successfully build tree "id", "_lft" and "parent_id" keys must present.
     *
     * If `$root` is provided, the tree will contain only descendants of that node.
     *
     * @param mixed $root
     *
     * @return Collection
     */
    public function toTree($root = false)
    {
        if ($this->isEmpty()) {
            return new static;
        }

        $this->linkNodes();

        $items = [ ];

        $root = $this->getRootNodeId($root);

        /** @var Model|NodeTrait $node */
        foreach ($this->items as $node) {
            if ($node->getParentId() == $root) {
                $items[] = $node;
            }
        }

        return new static($items);
    }

    /**
     * @param mixed $root
     *
     * @return int
     */
    protected function getRootNodeId($root = false)
    {
        if (NestedSet::isNode($root)) {
            return $root->getKey();
        }

        if ($root !== false) {
            return $root;
        }

        // If root node is not specified we take parent id of node with
        // least lft value as root node id.
        $leastValue = null;

        /** @var Model|NodeTrait $node */
        foreach ($this->items as $node) {
            if ($leastValue === null || $node->getLft() < $leastValue) {
                $leastValue = $node->getLft();
                $root = $node->getParentId();
            }
        }

        return $root;
    }

    /**
     * Build a list of nodes that retain the order that they were pulled from
     * the database.
     *
     * @param bool $root
     *
     * @return static
     */
    public function toFlatTree($root = false)
    {
        $result = new static;

        if ($this->isEmpty()) return $result;

        $groupedNodes = $this->groupBy($this->first()->getParentIdName());

        return $result->flattenTree($groupedNodes, $this->getRootNodeId($root));
    }

    /**
     * Flatten a tree into a non recursive array.
     *
     * @param Collection $groupedNodes
     * @param mixed $parentId
     *
     * @return $this
     */
    protected function flattenTree(self $groupedNodes, $parentId)
    {
        foreach ($groupedNodes->get($parentId, []) as $node) {
            $this->push($node);

            $this->flattenTree($groupedNodes, $node->getKey());
        }

        return $this;
    }

    /**
     * @param mixed $root
     *
     * @return Collection
     */
    public function toPluck($name, $key = null)
    {
        $menus = $this->toTree();
        return $menus->map(function($menu) use($name, $key) {
            return $this->nestedPluckItem($menu, $name, $key);
        })->all();
    }

    /**
     * @param mixed $root
     *
     * @return Collection
     */
    public function toList($columns = [])
    {
        $menus = $this->toTree()->toArray();
        if (count($columns) > 0) {
            return $this->nestedListItem($menus, $columns);
        }
        return $menus;
    }

    private function nestedPluckItem($menu, $name, $key = null)
    {
        if ($menu->children->count() > 0) {
            if ($menu->children->has('children')) {
                return $this->nestedPluckItem($menu->children, $name, $key);
            }
            return array_pluck($menu->children, $name, $key);
        }
        return array_pluck([$menu], $name, $key);
    }

    private function nestedListItem($menus, $columns = [])
    {
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
    }
}
