<?php

namespace Vnnit\Core\Traits;

use Illuminate\Support\Facades\DB;

trait CheckInUse
{
    abstract public function findTablesUsingModel();

    abstract public function getModelValue();

    public function isInUse()
    {
        $tables = $this->findTablesUsingModel();

        foreach ($tables as $tableColumn) {
            list($table, $column) = explode('.', $tableColumn);
            $exists = DB::table($table)->where($column, $this->getModelValue())->first();

            if ($exists) {
                return true;
            }
        }

        return false;
    }
}
