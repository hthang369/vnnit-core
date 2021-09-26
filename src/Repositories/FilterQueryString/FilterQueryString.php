<?php

namespace Vnnit\Core\Repositories\FilterQueryString;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Facades\Schema;

trait FilterQueryString {

    use Resolvings;

    public function setFilters(array $filters)
    {
        $this->filters = array_merge($this->filters, $filters);
        return $this;
    }

    public function filterByRequest()
    {
        $query = $this->model instanceof Builder ? $this->model : $this->query();

        $filters = collect($this->getFilters())->map(function ($values, $filter) {
            return $this->resolve($filter, $values);
        })->toArray();

        $this->model = app(Pipeline::class)
            ->send($query)
            ->through($filters)
            ->then(function (Builder $query) {
                return $this->postFilterByRequest($query);
            });

        return $this;
    }

    protected function postFilterByRequest(Builder $query)
    {
        return $this->defaultOrderBy($query);
    }

    protected function defaultOrderBy(Builder $query)
    {
        $defaultOrderByColumn = config('repository.default_order_by_column', 'created_at');
        $defaultOrderByValue = config('repository.default_order_by_value', 'desc');
        $model = $query->getModel();
        if (Schema::hasColumn($model->getTable(), $defaultOrderByColumn)) {
            return $query->orderBy($model->qualifyColumn($defaultOrderByColumn), $defaultOrderByValue);
        }
        return $query;
    }

    private function getFilters()
    {
        $filter = function ($key) {

            $filters = array_merge($this->baseFilters, $this->filters);
            $filters = array_keys($filters);

            return in_array($key, $filters);
        };

        return array_filter(request()->query(), $filter, ARRAY_FILTER_USE_KEY) ?? [];
    }
}
