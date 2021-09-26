<?php
namespace Vnnit\Core\Traits;

trait ConfiguresGrid
{
    protected function getGridView()
    {
        $prefix = config('vnnit-core.prefix');
        return "{$prefix}::components.data-grid";
    }
}