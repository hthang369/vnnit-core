<?php

namespace Vnnit\Core\Helpers;

use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Vnnit\Setting\Facade\Setting;

class Mysql
{
    const FORMAT_HOUR_MINUTE = '%H:%i';
    const FORMAT_TIME = '%H:%i:%s';
    const FORMAT_DATE_VN = '%d-%m-%Y';
    const FORMAT_DATE = '%Y-%m-%d';
    const FORMAT_YEAR_MONTH = '%Y-%m';

    public static function findTablesHavingColumn($column)
    {
        $array = DB::select(
            'SELECT DISTINCT TABLE_NAME, COLUMN_NAME
            FROM INFORMATION_SCHEMA.COLUMNS
            WHERE COLUMN_NAME LIKE :column
            AND TABLE_SCHEMA = :database;',
            [
                'database' => DB::getDatabaseName(),
                'column' => $column
            ]
        );

        $result = [];
        foreach ($array as $item) {
            $result[] = $item->TABLE_NAME . '.' . $item->COLUMN_NAME;
        }
        return $result;
    }

    public static function getStructTableColumns($table) {
        $array = DB::select(
            'SELECT DISTINCT INFORMATION_SCHEMA.COLUMNS.TABLE_NAME,
                INFORMATION_SCHEMA.COLUMNS.COLUMN_NAME,
                INFORMATION_SCHEMA.COLUMNS.ORDINAL_POSITION,
                INFORMATION_SCHEMA.COLUMNS.COLUMN_DEFAULT,
                INFORMATION_SCHEMA.COLUMNS.IS_NULLABLE,
                IF(schema_pre_encrypted.id IS NOT NULL, schema_pre_encrypted.DATA_TYPE, INFORMATION_SCHEMA.COLUMNS.DATA_TYPE) AS DATA_TYPE,
                IF(schema_pre_encrypted.id IS NOT NULL, schema_pre_encrypted.CHARACTER_MAXIMUM_LENGTH, INFORMATION_SCHEMA.COLUMNS.CHARACTER_MAXIMUM_LENGTH) AS DATA_LENGTH
            FROM INFORMATION_SCHEMA.COLUMNS
            LEFT JOIN schema_pre_encrypted ON INFORMATION_SCHEMA.COLUMNS.TABLE_NAME COLLATE utf8_unicode_ci = schema_pre_encrypted.TABLE_NAME
                                          AND INFORMATION_SCHEMA.COLUMNS.COLUMN_NAME COLLATE utf8_unicode_ci = schema_pre_encrypted.COLUMN_NAME
            WHERE INFORMATION_SCHEMA.COLUMNS.TABLE_NAME LIKE :table
            AND INFORMATION_SCHEMA.COLUMNS.TABLE_SCHEMA = :database;',
            [
                'database' => DB::getDatabaseName(),
                'table' => $table
            ]
        );
        return $array;
    }

	public static function getFullTextSearchColumns()
	{
		return Cache::remember('full_text_search_index',CACHE_EXPIRES,function() {
			return DB::select('SELECT TABLE_NAME,GROUP_CONCAT(COLUMN_NAME) COLUMN_NAME
			FROM INFORMATION_SCHEMA.STATISTICS
			WHERE TABLE_SCHEMA = :database AND INDEX_TYPE = :type_index
			GROUP BY TABLE_NAME,INDEX_NAME;',
			[
				'database' 	 => DB::getDatabaseName(),
				'type_index' => 'FULLTEXT'
			]);
		});
	}

	public static function hasFullTextSearchByColumnName($table_name,$column_name)
	{
		return collect(Mysql::getFullTextSearchColumns())->where('TABLE_NAME',$table_name)->where('COLUMN_NAME','LIKE',"%$column_name%")->count() > 0;
	}

    public static function rawToColumn($column, $isRaw = false)
    {
        if($isRaw) return DB::raw($column);
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
        $period = new CarbonPeriod(min($start,$end), '1 month', max($start,$end));
        $arr = [];
        foreach ($period->toArray() as $month){
            array_push($arr,sprintf("SELECT '%s' as date_field", $month->format(DEFAULT_DATE_FORMAT) ));
        }
        return implode(' UNION ',$arr);
    }

	public static function convertDateFormat($column, $format = '%Y-%m-%d', $isRaw = false)
    {
        return static::rawToColumn("DATE_FORMAT($column, '$format')", $isRaw);
    }
}
