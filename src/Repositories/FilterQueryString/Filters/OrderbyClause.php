<?php

namespace Vnnit\Core\Repositories\FilterQueryString\Filters;

use Illuminate\Database\Eloquent\Builder;

class OrderbyClause extends BaseClause {

    protected $orderValueKey = 'direction';
    protected $defaultSortValue = 'asc';

    protected function apply($query): Builder
    {
        $sortValue = $this->getSortValue();

        $query->orderBy($this->values, $sortValue);

        return $query;
    }

    public function validate($value): bool {
        return !is_null($value);
    }

    protected function getSortValue()
    {
        $request = request();
        return $request->has($this->orderValueKey) ? $request->get($this->orderValueKey) : $this->defaultSortValue;
    }
}
