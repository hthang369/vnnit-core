<?php

namespace Vnnit\Core\Traits;

use Vnnit\Core\Support\Mysql;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Query\Expression;
/*
 * A trait to handle use full text search
 */

trait FullTextSearch
{
    /**
     * Replaces spaces with full text search wildcards
     *
     * @param string $term
     * @return string
     */
    protected function fullTextWildcards($term)
    {
        // removing symbols used by MySQL
        $reservedSymbols = ['-', '+', '<', '>', '@', '(', ')', '~'];
        $term = str_replace($reservedSymbols, '', $term);

        $words = explode(' ', $term);

        foreach ($words as $key => $word) {
            /*
             * applying + operator (required word) only big words
             * because smaller ones are not indexed by mysql
             */
            if (strlen($word) >= 2) {
                $words[$key] = '+' . $word . '*';
            }
        }

        $searchTerm = implode(' ', $words);

        return $searchTerm;
    }

    /**
     * Scope a query that matches a full text search of term.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $term
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeMatchSearch($query, $term)
    {
        $columns = implode(',', $this->searchable);

        $query->whereRaw("MATCH ({$columns}) AGAINST (? IN BOOLEAN MODE)", $this->fullTextWildcards($term));

        return $query;
    }

    public function getSearchable()
    {
        return $this->searchable ?? [];
    }

    public function hasSearchables()
    {
        return (!is_null($this->searchable) && count($this->searchable) > 0);
    }

    public function hasSearchableByColumn($column_name)
    {
        return ($this->hasSearchables() && in_array($column_name, $this->searchable));
    }

    public function hasFullTextSearch($column_name)
    {
        return $this->hasSearchableByColumn($column_name) && Mysql::hasFullTextSearchByColumnName($this->getTable(), $column_name);
    }

    public function scopeFullTextSearch($query, $column_name, $value)
    {
        $column_name_value = ($column_name instanceof Expression) ? $column_name->getValue() : $column_name;
        $value = trim($value);
        if ($this->hasFullTextSearch($column_name_value)) {
            return $this->scopeMatchSearch($query, $value);
        }
        return $query->where(function ($where) use ($column_name, $column_name_value, $value) {
            $where->where($column_name, 'LIKE', "%$value%")
                ->orWhere(DB::raw("fnc_LocDauTV($column_name_value) COLLATE utf8_unicode_ci"), 'LIKE', "%$value%");
        });
    }
}
