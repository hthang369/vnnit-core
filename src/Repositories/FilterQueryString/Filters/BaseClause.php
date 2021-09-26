<?php

namespace Vnnit\Core\Repositories\FilterQueryString\Filters;

use Illuminate\Database\Eloquent\Builder;

abstract class BaseClause {

    protected $query;
    protected $column;
    protected $values;

    public function __construct($values, $column) {

        $this->values = $values;
        $this->column = $column;
    }

    public function handle($query, $nextFilter)
    {
        $this->query = $query;

        if(static::validate($this->values) === false) {
            return $this->query;
        }

        static::apply($this->query);

        return $nextFilter($query);
    }

    abstract protected function apply($query): Builder;

    abstract protected function validate($value): bool;

}
