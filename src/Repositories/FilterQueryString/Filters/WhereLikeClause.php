<?php

namespace Vnnit\Core\Repositories\FilterQueryString\Filters;

use Illuminate\Database\Eloquent\Builder;

class WhereLikeClause extends BaseClause {

    public function apply($query): Builder
    {
        $query->where($this->column, 'like', "%$this->values%");

        return $query;
    }

    public function validate($value): bool
    {
        if (is_null($value)) {
            return false;
        }

        return true;
    }
}
