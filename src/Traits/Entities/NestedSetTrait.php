<?php

namespace Vnnit\Core\Traits\Entities;

use Vnnit\Core\Plugins\Nestedset\NestedSet;
use Vnnit\Core\Plugins\Nestedset\NodeTrait;

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
}
