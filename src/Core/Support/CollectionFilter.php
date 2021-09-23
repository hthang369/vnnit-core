<?php

namespace Vnnit\Core\Support;

use Illuminate\Support\Collection;

class CollectionFilter
{
    protected $filter = [];
    protected $customFilter = [];
    protected $except = [];

    public function fromRequest()
    {
        $query = request()->input('query');
        if (!is_array($query)) {
            $this->filter = json_decode(request()->input('query'), true);
        } else {
            $this->filter = $query;
        }
        if (is_array($this->filter)) {
            $this->filter = array_filter($this->filter, function ($key) {
                $key = preg_replace('/__like$/', '', $key);
                return !in_array($key, $this->except);
            }, ARRAY_FILTER_USE_KEY);
        }
    }

    public function setCustomFilter($key, $value)
    {
        $this->customFilter[$key] = $value;
    }

    public function handle(Collection $data)
    {
        if (empty($this->filter)) {
            return $data;
        }

        foreach ($this->filter as $filterKey => $filterValue) {
            $data = $data->filter(function ($value) use ($filterKey, $filterValue) {
                $method = '';
                if (strpos($filterKey, '__like') !== false) {
                    $filterKey = preg_replace('/__like$/', '', $filterKey);
                    $method = 'like';
                }

                if (isset($this->customFilter[$filterKey]) && $this->customFilter[$filterKey] instanceof \Closure) {
                    return $this->customFilter[$filterKey]($filterValue, $value, $method);
                }

                if (is_array($filterValue) && isset($filterValue['start']) && isset($filterValue['end'])) {
                    return $value[$filterKey] >= $filterValue['start'] && $value[$filterKey] <= $filterValue['end'];
                }

                if (!isset($value[$filterKey])) {
                    return true;
                }

                if ($method == 'like') {
                    return strpos($value[$filterKey], $filterValue) !== false;
                } else {
                    return $filterValue == $value[$filterKey];
                }
            });
        }

        return $data;
    }

    public function except(array $array)
    {
        $this->except = $array;
    }
}
