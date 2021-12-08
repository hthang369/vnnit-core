<?php
namespace Vnnit\Core\Plugins\Widgets;

use Illuminate\Support\Facades\Blade;
use Vnnit\Core\BaseServiceProvider;
use Vnnit\Core\Plugins\Widgets\Factories\WidgetFactory;

class WidgetServiceProvider extends BaseServiceProvider
{
    protected $facades = [
        'widget' => WidgetFactory::class
    ];
    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        Blade::directive('widget', function ($expression) {
            return "<?php echo app('widget')->run($expression); ?>";
        });

        Blade::directive('asyncWidget', function ($expression) {
            return "<?php echo app('async-widget')->run($expression); ?>";
        });

        Blade::directive('widgetGroup', function ($expression) {
            return "<?php echo app('widget-group-collection')->group($expression)->display(); ?>";
        });
    }
}
