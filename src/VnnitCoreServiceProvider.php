<?php

namespace Vnnit\Core;

use Collective\Html\FormFacade as Form;
use Illuminate\Support\Facades\Blade;
use Vnnit\Core\Providers\BaseServiceProvider;

class VnnitCoreServiceProvider extends BaseServiceProvider
{
    public function boot()
    {
        
        // $prefix = config('laka-core.prefix');

        // $this->loadViewsFrom(__DIR__.'/../resources/views', $prefix);

        // $this->loadTranslationsFrom(__DIR__.'/../resources/lang', $prefix);

        // $this->registerBladeComponents();

        // $this->registerFormComponents();

        // $this->loadHelperFile();

        // if ($this->app->runningInConsole()) {
        //     $this->publishes([
        //         __DIR__ . '/../../config/config.php' => config_path('laka-core.php'),
        //     ], 'config');
        // }
    }

    private function loadHelperFile()
    {
        require_once(__DIR__.'/helpers.php');
    }

    public function register()
    {
        // $this->mergeConfigFrom(__DIR__ . '/../config/config.php', 'laka-core');
    }

    protected function registerBladeComponents()
    {
        collect(config('laka-core.components'))->each(function($item, $alias) {
            Blade::component($alias, $item['class']);
        });

        Blade::directive('icon', function ($expression) {
            return '<i class="'."<?php echo e($expression); ?>".'"></i>';
        });
    }

    protected function registerFormComponents()
    {
        collect(config('laka-core.form-components'))->each(function($item, $alias) {
            Form::component($alias, $item['view'], $item['params']);
        });
    }
}
