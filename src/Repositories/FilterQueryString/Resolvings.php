<?php

namespace Vnnit\Core\Repositories\FilterQueryString;

use Vnnit\Core\Exceptions\RepositoryException;

trait Resolvings
{
    private function resolve($filterName, $values)
    {
        if ($this->isCustomFilter($filterName)) {
            return $this->resolveCustomFilter($filterName, $values);
        }

        $filters = array_merge($this->baseFilters, $this->filters);

        if (!isset($filters[$filterName])) {
            throw new RepositoryException('Unknown filter column: ' . $filterName);
        }

        $filterClause = $filters[$filterName];

        return app($filterClause, ['column' => $filterName, 'values' => $values]);
    }

    private function isCustomFilter($filterName)
    {
        return method_exists($this, $filterName);
    }

    private function resolveCustomFilter($filterName, $values)
    {
        return $this->getClosure($this->makeCallable($filterName), $values);
    }

    private function getClosure($callable, $values)
    {
        return function ($query, $nextFilter) use ($callable, $values) {
            return app()->call($callable, ['query' => $nextFilter($query), 'value' => $values]);
        };
    }

    private function makeCallable($filter)
    {
        return static::class . '@' . $filter;
    }
}
