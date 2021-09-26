<?php

namespace Vnnit\Core;

use Illuminate\Support\ServiceProvider;

class BaseServiceProvider extends ServiceProvider
{
    protected $facades = [];

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
}
