<?php

namespace Vnnit\Core\Traits;

use Vnnit\Core\Support\Mysql;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

trait RequestParamTrait
{
    protected $orderBy;
    protected $foreignTables = [];
    protected $structTable;
    protected $primaryTable;
    protected $defaultParams;

    private function getModel()
    {
        return $this->model instanceof Builder ? $this->model->getModel() : $this->model;
    }

    private function configParams() : void
    {
        $this->defaultParams = ['orderBy' => null, 'perPage' => 30, 'select' => []];
        foreach ($this->defaultParams as $param => $value) {
            if ($this->{$param}) continue;
            $this->{$param} = request($param, $value) ?? $value;
        }
        if (is_string($this->select)) {
            $this->select = explode(',', $this->select);
        }

        $listColumn = array_merge($this->getModel()->getFillableColumns(), $this->select);
        $this->select = [];
        foreach (array_reverse($listColumn) as $column)
        {
            $list = explode(':', $column);
            if (count($list) == 1) {
                array_unshift($this->select, $column);
            } else {
                list($columnAlias, $table) = $list;
                list($foreignTable, $foreignKey, $columnName) = explode(',', $table, 3);
                array_unshift($this->select, DB::raw("$foreignTable.$columnName AS $columnAlias"));
                array_unshift($this->foreignTables, $table);
            }
        }
    }

    private function getStructInfoTable() : void
    {
        $this->primaryTable = $tableName = $this->getModel()->getTable();
        $this->structTable = collect(Mysql::getStructTableColumns($tableName))->groupBy('COLUMN_NAME');
    }

    protected function applyCriteria()
    {
        $this->configParams();
        $model = $this->model instanceof Builder ? $this->model : $this->model->query();

        if (count($this->foreignTables) > 0) {
            $this->getStructInfoTable();
            $idx = array_search('id', $this->select);
            data_set($this->select, $idx, DB::raw($this->primaryTable.'.'.$this->select[$idx]));
            foreach ($this->foreignTables as $table) {
                list($tableName, $foreignKey, $columnName) = explode(',', $table, 3);
                if ($this->structTable->has($foreignKey)) {
                    $structForeignKey = $this->structTable->get($foreignKey)->first();
                    $primaryTable = data_get($structForeignKey, 'TABLE_NAME');
                    $ColumnNullable = data_get($structForeignKey, 'IS_NULLABLE', 'NO');
                    $tableJoin = $ColumnNullable == 'NO' ? 'join' : 'leftJoin';
                    $model->{$tableJoin}($tableName, $tableName.'.id', '=', $primaryTable.'.'.$foreignKey);
                }
            }
        }

        $this->model = $model;

        return $this->filterByRequest();
    }

    /**
     * Reset Query Scope
     *
     * @return $this
     */
    public function resetScope()
    {
        $this->scopeQuery = null;

        return $this;
    }

    /**
     * Apply scope in current Query
     *
     * @return $this
     */
    protected function applyScope()
    {
        if (isset($this->scopeQuery) && is_callable($this->scopeQuery)) {
            $callback = $this->scopeQuery;
            $this->model = $callback($this->model);
        }

        return $this;
    }

    /**
     * Applies the given where conditions to the model.
     *
     * @param array $where
     * @return void
     */
    protected function applyConditions(array $where)
    {
        foreach ($where as $field => $value) {
            if (is_array($value)) {
                list($field, $condition, $val) = $value;
                $this->model = $this->model->where($field, $condition, $val);
            } else {
                $this->model = $this->model->where($field, '=', $value);
            }
        }
    }
}
