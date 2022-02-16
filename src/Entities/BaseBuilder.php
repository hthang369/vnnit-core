<?php
namespace Vnnit\Core\Entities;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\DB;
use Vnnit\Core\Traits\Pagination\BuildPaginator;

class BaseBuilder extends Builder
{
    use BuildPaginator;

    public function paginateNestedTree($perPage = null, $columns = ['*'], $pageName = 'page', $page = null)
    {
        $page = $page ?: Paginator::resolveCurrentPage($pageName);

        $perPage = $perPage ?: $this->model->getPerPage();

        $results = ($total = $this->toBase()->getCountForPagination())
                                    ? $this->defaultDepthNestedTree()->forPage($page, $perPage)->get($columns)
                                    : $this->model->newCollection();

        return $this->paginator($results, $total, $perPage, $page, [
            'path' => Paginator::resolveCurrentPath(),
            'pageName' => $pageName,
        ]);
    }

    public function defaultDepthNestedTree()
    {
        $tableName = $this->model->getTable();
        $lftName = $this->model->getLftName();
        $rgtName = $this->model->getRgtName();
        $keyName = $this->model->getKeyName();
        array_walk($this->query->wheres, function(&$item) {
            data_set($item, 'column', $this->model->qualifyColumn($item['column']));
        });
        $this->query->select([$tableName.'.*', DB::raw("(COUNT(parentTbl.{$keyName}) - 1) depth")])
            ->crossJoin("{$tableName} as parentTbl")
            ->whereBetweenColumns($tableName.'.'.$lftName, ['parentTbl.'.$lftName, 'parentTbl.'.$rgtName])
            ->groupBy($this->model->getQualifiedKeyName());

        return $this->defaultOrder();
    }
}
