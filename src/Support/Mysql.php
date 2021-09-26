<?php

namespace Vnnit\Core\Support;

use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\LazyCollection;

class Mysql
{
    const FORMAT_HOUR_MINUTE = '%H:%i';
    const FORMAT_TIME = '%H:%i:%s';
    const FORMAT_DATE_VN = '%d-%m-%Y';
    const FORMAT_DATE = '%Y-%m-%d';
    const FORMAT_YEAR_MONTH = '%Y-%m';
    const CACHE_EXPIRES = 10;

    public static function findTablesHavingColumn($column)
    {
        $result = Cache::remember('struct_table_having_'.$column, self::CACHE_EXPIRES, function() use($column) {
            return LazyCollection::make(function() use($column) {
                return DB::cursor('SELECT DISTINCT TABLE_NAME, COLUMN_NAME
                                FROM INFORMATION_SCHEMA.COLUMNS
                                WHERE COLUMN_NAME LIKE :column
                                AND TABLE_SCHEMA = :database;',
                                ['database' => DB::getDatabaseName(), 'column' => $column]);
            })
            ->map(function($item) {
                return $item->TABLE_NAME.'.'.$item->COLUMN_NAME;
            })
            ->all();
        });
        return $result;
    }

    public static function getStructTableColumns($table)
    {
        $result = Cache::remember('struct_'.$table, self::CACHE_EXPIRES, function() use($table) {
            return LazyCollection::make(function() use($table) {
                return DB::cursor('SELECT DISTINCT TABLE_NAME,
                                    COLUMN_NAME,
                                    ORDINAL_POSITION,
                                    COLUMN_DEFAULT,
                                    IS_NULLABLE,
                                    DATA_TYPE,
                                    CHARACTER_MAXIMUM_LENGTH AS DATA_LENGTH
                                FROM INFORMATION_SCHEMA.COLUMNS
                                WHERE TABLE_NAME = :table
                                AND TABLE_SCHEMA = :database;',
                                ['database' => DB::getDatabaseName(), 'table' => $table]);
            })
            ->map(function($item) {
                return (array) $item;
            })
            ->all();
        });
        return $result;
    }

    public static function getStructConstraintTable($table)
    {
        return Cache::remember("struct_constraint_$table", self::CACHE_EXPIRES, function () use($table) {
            return DB::select('SELECT tbl.TABLE_NAME, CONSTRAINT_TYPE, DELETE_RULE, UPDATE_RULE, REFERENCED_TABLE_NAME
                        FROM information_schema.TABLE_CONSTRAINTS AS tbl
                        JOIN information_schema.REFERENTIAL_CONSTRAINTS AS ref ON tbl.CONSTRAINT_SCHEMA = ref.CONSTRAINT_SCHEMA
                            AND tbl.CONSTRAINT_NAME = ref.CONSTRAINT_NAME
                        WHERE tbl.TABLE_SCHEMA = DATABASE() AND CONSTRAINT_TYPE = ? AND tbl.TABLE_NAME = ?
            ', ['FOREIGN KEY', $table]);
        });
    }

    public static function getFullTextSearchColumns()
    {
        return Cache::remember('full_text_search_index', self::CACHE_EXPIRES, function () {
            return DB::select(
                'SELECT TABLE_NAME,GROUP_CONCAT(COLUMN_NAME) COLUMN_NAME
			FROM INFORMATION_SCHEMA.STATISTICS
			WHERE TABLE_SCHEMA = :database AND INDEX_TYPE = :type_index
			GROUP BY TABLE_NAME,INDEX_NAME;',
                [
                'database'   => DB::getDatabaseName(),
                'type_index' => 'FULLTEXT'
                ]
            );
        });
    }

    public static function hasFullTextSearchByColumnName($table_name, $column_name)
    {
        return collect(Mysql::getFullTextSearchColumns())->where('TABLE_NAME', $table_name)->where('COLUMN_NAME', 'LIKE', "%$column_name%")->count() > 0;
    }

    public static function rawToColumn($column, $isRaw = false)
    {
        if ($isRaw) {
            return DB::raw($column);
        }
        return $column;
    }

    public static function formatNullToValue($column, $value, $isRaw = false)
    {
        $str = "IFNULL($column, $value)";
        return static::rawToColumn($str, $isRaw);
    }

    public static function formatNullToZero($column, $isRaw = false)
    {
        return static::formatNullToValue($column, 0, $isRaw);
    }

    public static function formatNullToDash($column, $isRaw = false)
    {
        return static::formatNullToValue($column, '-', $isRaw);
    }

    public static function formatMonthFromDate($column, $isRaw = false)
    {
        return static::rawToColumn("EXTRACT(YEAR_MONTH FROM $column)", $isRaw);
    }

    public static function formatSecondToTime($column, $isRaw = false)
    {
        return static::rawToColumn("SEC_TO_TIME($column)", $isRaw);
    }

    public static function formatTimeToSecond($column, $isRaw = false)
    {
        return static::rawToColumn("TIME_TO_SEC($column)", $isRaw);
    }

    public static function convertTimeFormat($column, $format = '%H:%i', $isRaw = false)
    {
        return static::rawToColumn("TIME_FORMAT($column, '$format')", $isRaw);
    }

    public static function generateMonthDate($start, $end)
    {
        $period = new CarbonPeriod(min($start, $end), '1 month', max($start, $end));
        $arr = [];
        foreach ($period->toArray() as $month) {
            array_push($arr, sprintf("SELECT '%s' as date_field", $month->toDateString()));
        }
        return implode(' UNION ', $arr);
    }

    public static function convertDateFormat($column, $format = '%Y-%m-%d', $isRaw = false)
    {
        return static::rawToColumn("DATE_FORMAT($column, '$format')", $isRaw);
    }
}
