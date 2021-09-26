<?php
namespace Vnnit\Core\Traits;

use Illuminate\Container\Container;
use Illuminate\Pagination\Paginator;
use Vnnit\Core\Pagination\LakaPagination;

trait BuildPaginator
{
    protected function paginator($items, $total, $perPage, $currentPage, $options)
    {
        $options = array_merge($options, ['path' => Paginator::resolveCurrentPath()]);
        return Container::getInstance()->makeWith(LakaPagination::class, compact(
            'items', 'total', 'perPage', 'currentPage', 'options'
        ));
    }
}
