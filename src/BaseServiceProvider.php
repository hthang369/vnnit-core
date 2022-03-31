<?php

namespace Vnnit\Core;

use Illuminate\Console\Command;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use ReflectionClass;

class BaseServiceProvider extends ServiceProvider
{
    protected $facades = [];
    protected $moduleNamespace = '';
    protected $modulePath = '';
    protected $commandPath = '';


    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerFacades();
    }

    /**
     * Register facade.
     *
     * @return void
     */
    protected function registerFacades()
    {
        foreach($this->facades as $key => $class) {
            $this->app->singleton($key, function () use($class) {
                return new $class();
            });
        }
    }

    /**
     * Register Commands
     *
     * @return void
     */
    public function registerCommands()
    {
        if (blank($this->commandPath)) return;
        $listCommands = array_map(function ($fileInfo) {
            $command = $this->moduleNamespace.str_replace(
                ['/', '.php'],
                ['\\', ''],
                Str::after($fileInfo->getRealPath(), realpath($this->modulePath).DIRECTORY_SEPARATOR)
            );
            if (is_subclass_of($command, Command::class) &&
                ! (new ReflectionClass($command))->isAbstract()) {
                    return $command;
                }
        }, File::allFiles($this->commandPath));
        $this->commands(array_filter($listCommands));
    }
}
