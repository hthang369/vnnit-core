<?php

namespace Vnnit\Core\Repositories\FilterQueryString\Filters;

use Illuminate\Database\Eloquent\Builder;

class WhereClause extends BaseClause {

    protected function apply($query): Builder
    {
        $query->where($this->column, $this->values);

        return $query;
    }

    protected function validate($value): bool {
        return !is_null($value);
    }
}
