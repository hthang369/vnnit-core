<?php
namespace Vnnit\Core\Plugins\FileManager;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class LfmServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if (config('file-manager.use_package_routes')) {
            Route::group(['prefix' => 'admin/filemanager', 'middleware' => ['web', 'auth']], function () {
                \Vnnit\Core\Plugins\FileManager\Lfm::routes();
            });
        }
    }
}
