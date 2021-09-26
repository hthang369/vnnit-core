<?php

namespace Vnnit\Core;

use Collective\Html\FormFacade as Form;
use Illuminate\Support\Facades\Blade;
use Vnnit\Core\Support\CommonHelper;
use Vnnit\Core\Support\FileManagementService;

class VnnitCoreServiceProvider extends BaseServiceProvider
{
    protected $facades = [
        'file-management' => FileManagementService::class,
        'common-helper' => CommonHelper::class
    ];

    public function boot()
    {
        $prefix = config('vnnit-core.prefix');

        $this->loadViewsFrom(__DIR__.'/../resources/views', $prefix);

        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', $prefix);

        $this->registerBladeComponents();

        $this->registerFormComponents();

        $this->loadHelperFile();

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../../config/config.php' => config_path('vnnit-core.php'),
            ], 'config');
        }
    }

    private function loadHelperFile()
    {
        require_once(__DIR__.'/helpers.php');
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/config.php', 'vnnit-core');
        $this->mergeConfigFrom(__DIR__ . '/../config/permission.php', 'permission');
        parent::register();
    }

    protected function registerBladeComponents()
    {
        collect(config('vnnit-core.components'))->each(function($item, $alias) {
            Blade::component($alias, $item['class']);
        });

        Blade::directive('icon', function ($expression) {
            return '<i class="'."<?php echo e($expression); ?>".'"></i>';
        });
    }

    protected function registerFormComponents()
    {
        collect(config('vnnit-core.bt-components'))->each(function($item, $alias) {
            Form::component($alias, $item['view'], $item['params']);
        });
    }
}
