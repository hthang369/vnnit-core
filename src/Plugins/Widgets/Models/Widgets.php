<?php
namespace Vnnit\Core\Plugins\Widgets\Models;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Vnnit\Core\Entities\BaseModel;

class Widgets extends BaseModel
{
    protected $table = 'setting_details';

    protected $fillable = [
        'key',
        'value'
    ];

    public function getWidgetId()
    {
        return Cache::remember('widget_id', 10, function () {
            $setting = DB::table('settings')->where('name', 'widget')->pluck('name', 'id');
            return $setting->keys()->first();
        });
    }

    public function findWidget($name)
    {
        return $this->where(['key' => $name, 'setting_id' => $this->getWidgetId()])->first();
    }
}
