<?php
namespace Vnnit\Core\Plugins\Widgets\Factories;

use Illuminate\Support\Facades\Cache;
use Vnnit\Core\Plugins\Widgets\Models\Widgets;

abstract class AbstractWidgetFactory
{
    /**
     * Widget object to work with.
     *
     * @var Widget
     */
    protected $widget;

    /**
     * Widget model object.
     *
     * @var WidgetModel
     */
    protected $model;

    /**
     * Widget configuration array.
     *
     * @var array
     */
    protected $widgetConfig;

    /**
     * The name of the widget being called.
     *
     * @var string
     */
    public $widgetName;

    public function __construct()
    {
        $this->model = resolve(Widgets::class);
        $this->widgetConfig['widget_object'] = config('vnnit-core.plugins.widget.object');
    }

    /**
     * Set class properties and instantiate a widget object.
     *
     * @param $params
     *
     * @throws InvalidWidgetClassException
     * @throws EncryptException
     */
    protected function instantiateWidget(array $params = [])
    {
        if (str_is($this->widgetConfig['widget_object'], 'table')) {
            $key = head($params);
            $column = count($params) == 1 ? 'text' : end($params);
            $this->widgetConfig['widget_column'] = $column;
            $this->widget = Cache::remember("widget_{$key}", 10, function () use($params) {
                return $this->model->findWidget(head($params));
            });
        }
    }

    public function render()
    {
        if (str_is($this->widgetConfig['widget_object'], 'table')) {
            $widget_obj = json_decode(data_get($this->widget, 'value'));
            return data_get($widget_obj, $this->widgetConfig['widget_column']);
        }
        return '';
    }
}
