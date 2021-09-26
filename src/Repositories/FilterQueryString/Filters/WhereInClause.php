<?php

namespace Vnnit\Core\Repositories\FilterQueryString\Filters;

use Illuminate\Database\Eloquent\Builder;

class WhereInClause extends BaseClause {

    public function apply($query): Builder
    {
        $values = $this->normalizeValues();

        return $query->whereIn($this->column, $values);
    }

    public function validate($value): bool
    {
        if(is_null($value)) {
            return false;
        }

        return true;
    }

    private function normalizeValues()
    {
        return explode(',', $this->values);
    }
}
