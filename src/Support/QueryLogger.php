<?php

namespace Vnnit\Core\Support;

use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Support\Facades\Log;

class QueryLogger
{
    public static function log(QueryExecuted $query)
    {
        // https://gist.github.com/developerdino/9124175
        foreach ($query->bindings as $i => $binding) {
            if ($binding instanceof \DateTime) {
                $query->bindings[ $i ] = $binding->format('\'Y-m-d H:i:s\'');
            } else {
                if (is_string($binding)) {
                    $query->bindings[ $i ] = "'$binding'";
                }
            }
        }

        // Insert bindings into query
        $boundSql = str_replace(['%', '?'], ['%%', '%s'], $query->sql);
        $boundSql = vsprintf($boundSql, $query->bindings);
        echo $boundSql;
        Log::channel('query')->info($boundSql);
    }
}
