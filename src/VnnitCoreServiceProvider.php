<?php

namespace Vnnit\Core;

use Collective\Html\FormFacade as Form;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Route;
use Vnnit\Core\Plugins\FileManager\LfmServiceProvider;
use Vnnit\Core\Plugins\Widgets\WidgetServiceProvider;
use Vnnit\Core\Support\CommonHelper;
use Vnnit\Core\Support\FileManagementService;
use Vnnit\Core\Support\ModalHelper;

class VnnitCoreServiceProvider extends BaseServiceProvider
{
    protected $facades = [
        'file-management' => FileManagementService::class,
        'common-helper' => CommonHelper::class,
        'modal'  => ModalHelper::class
    ];

    protected $publishConfigs = [
        'vnnit-core' => 'config.php',
        'permission' => 'permission.php',
        'form-builder' => 'form-builder.php',
        'file-manager' => 'file-manager.php'
    ];

    protected $moduleNamespace = 'Vnnit\\Core\\';
    protected $modulePath = __DIR__;
    protected $commandPath = __DIR__.'\\Console';

    public function boot()
    {
        $prefix = config('vnnit-core.prefix');

        $this->loadViewsFrom(__DIR__.'/../resources/views', $prefix);

        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', $prefix);

        $this->registerBladeComponents();

        $this->registerFormComponents();

        $this->loadHelperFile();

        $this->registerCommands();

        // $this->app->alias('Modal', ModalHelper::class);

        if ($this->app->runningInConsole()) {
            foreach($this->publishConfigs as $key => $file) {
                $this->publishes([
                    __DIR__ . '/../config/'.$file => config_path("{$key}.php"),
                ], 'config');
            }
        }
    }

    private function loadHelperFile()
    {
        require_once(__DIR__.'/helpers.php');
    }

    public function register()
    {
        foreach($this->publishConfigs as $key => $file) {
            $this->mergeConfigFrom(__DIR__ . '/../config/'.$file, $key);
        }
        $this->app->register(LfmServiceProvider::class);
        $this->app->register(WidgetServiceProvider::class);
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
        $prefix = config('vnnit-core.prefix');
        collect(config('vnnit-core.bt-components'))->each(function($item, $alias) use($prefix) {
            Form::component($alias, $prefix.'::'.$item['view'], $item['params']);
        });
    }

    public function provides()
    {
        return ['modal', ModalHelper::class];
    }
}
