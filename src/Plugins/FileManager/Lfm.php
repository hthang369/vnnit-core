<?php

namespace Vnnit\Core\Plugins\FileManager;

use Illuminate\Contracts\Config\Repository as Config;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Vnnit\Core\Plugins\FileManager\Controllers\CropController;
use Vnnit\Core\Plugins\FileManager\Controllers\DeleteController;
use Vnnit\Core\Plugins\FileManager\Controllers\DownloadController;
use Vnnit\Core\Plugins\FileManager\Controllers\FolderController;
use Vnnit\Core\Plugins\FileManager\Controllers\ItemsController;
use Vnnit\Core\Plugins\FileManager\Controllers\LfmController;
use Vnnit\Core\Plugins\FileManager\Controllers\RenameController;
use Vnnit\Core\Plugins\FileManager\Controllers\ResizeController;
use Vnnit\Core\Plugins\FileManager\Controllers\UploadController;
use Vnnit\Core\Plugins\FileManager\Middlewares\CreateDefaultFolder;
use Vnnit\Core\Plugins\FileManager\Middlewares\MultiUser;

class Lfm
{
    const PACKAGE_NAME = 'laravel-filemanager';
    const DS = '/';

    protected $config;
    protected $request;

    public function __construct(Config $config = null, Request $request = null)
    {
        $this->config = $config;
        $this->request = $request;
    }

    public function getStorage($storage_path)
    {
        return new LfmStorageRepository($storage_path, $this);
    }

    public function input($key)
    {
        return $this->translateFromUtf8($this->request->input($key));
    }

    public function config($key, $default = null)
    {
        return $this->config->get('file-manager.' . $key, $default);
    }

    /**
     * Get only the file name.
     *
     * @param  string  $path  Real path of a file.
     * @return string
     */
    public function getNameFromPath($path)
    {
        return $this->utf8Pathinfo($path, 'basename');
    }

    public function utf8Pathinfo($path, $part_name)
    {
        // XXX: all locale work-around for issue: utf8 file name got emptified
        // if there's no '/', we're probably dealing with just a filename
        // so just put an 'a' in front of it
        if (strpos($path, '/') === false) {
            $path_parts = pathinfo('a' . $path);
        } else {
            $path = str_replace('/', '/a', $path);
            $path_parts = pathinfo($path);
        }

        return substr($path_parts[$part_name], 1);
    }

    public function allowFolderType($type)
    {
        if ($type == 'user') {
            return $this->allowMultiUser();
        } else {
            return $this->allowShareFolder();
        }
    }

    public function getCategoryName()
    {
        $type = $this->currentLfmType();

        return $this->config('folder_categories.' . $type . '.folder_name', 'files');
    }

    /**
     * Get current lfm type.
     *
     * @return string
     */
    public function currentLfmType()
    {
        $lfm_type = 'all';

        $request_type = lcfirst(Str::singular($this->input('type') ?: ''));
        $available_types = array_keys($this->config('folder_categories') ?: []);

        if (in_array($request_type, $available_types)) {
            $lfm_type = $request_type;
        }

        return $lfm_type;
    }

    public function getDisplayMode()
    {
        $type_key = $this->currentLfmType();
        $startup_view = $this->config('folder_categories.' . $type_key . '.startup_view');

        $view_type = 'grid';
        $target_display_type = $this->input('show_list') ?: $startup_view;

        if (in_array($target_display_type, ['list', 'grid'])) {
            $view_type = $target_display_type;
        }

        return $view_type;
    }

    public function getUserSlug()
    {
        $config = $this->config('private_folder_name');

        if (is_callable($config)) {
            return call_user_func($config);
        }

        if (class_exists($config)) {
            return app()->make($config)->userField();
        }

        return empty(auth()->user()) ? '' : auth()->user()->$config;
    }

    public function getRootFolder($type = null)
    {
        if (is_null($type)) {
            $type = 'share';
            if ($this->allowFolderType('user')) {
                $type = 'user';
            }
        }

        if ($type === 'user') {
            $folder = $this->getUserSlug();
        } else {
            $folder = $this->config('shared_folder_name');
        }

        // the slash is for url, dont replace it with directory seperator
        return '/' . $folder;
    }

    public function getThumbFolderName()
    {
        return $this->config('thumb_folder_name');
    }

    public function getFileType($ext)
    {
        return $this->config("file_type_array.{$ext}", 'File');
    }

    public function availableMimeTypes()
    {
        return $this->config('folder_categories.' . $this->currentLfmType() . '.valid_mime');
    }

    public function maxUploadSize()
    {
        return $this->config('folder_categories.' . $this->currentLfmType() . '.max_size');
    }

    public function getPaginationPerPage()
    {
        return $this->config("paginator.perPage", 30);
    }

    /**
     * Check if users are allowed to use their private folders.
     *
     * @return bool
     */
    public function allowMultiUser()
    {
        return $this->config('allow_private_folder') === true;
    }

    /**
     * Check if users are allowed to use the shared folder.
     * This can be disabled only when allowMultiUser() is true.
     *
     * @return bool
     */
    public function allowShareFolder()
    {
        if (! $this->allowMultiUser()) {
            return true;
        }

        return $this->config('allow_shared_folder') === true;
    }

    /**
     * Translate file name to make it compatible on Windows.
     *
     * @param  string  $input  Any string.
     * @return string
     */
    public function translateFromUtf8($input)
    {
        if ($this->isRunningOnWindows()) {
            $input = iconv('UTF-8', mb_detect_encoding($input), $input);
        }

        return $input;
    }

    /**
     * Get directory seperator of current operating system.
     *
     * @return string
     */
    public function ds()
    {
        $ds = Lfm::DS;
        if ($this->isRunningOnWindows()) {
            $ds = '\\';
        }

        return $ds;
    }

    /**
     * Check current operating system is Windows or not.
     *
     * @return bool
     */
    public function isRunningOnWindows()
    {
        return strtoupper(substr(PHP_OS, 0, 3)) === 'WIN';
    }

    /**
     * Shorter function of getting localized error message..
     *
     * @param  mixed  $error_type  Key of message in lang file.
     * @param  mixed  $variables   Variables the message needs.
     * @return string
     */
    public function error($error_type, $variables = [])
    {
        throw new \Exception(translate('lfm.error-' . $error_type, $variables));
    }

    /**
     * Generates routes of this package.
     *
     * @return void
     */
    public static function routes()
    {
        $middleware = [ CreateDefaultFolder::class, MultiUser::class ];
        $as = 'lfm.';

        Route::group(compact('middleware', 'as'), function () {

            // display main layout
            Route::get('/', [LfmController::class, 'show'])->name('show');

            // display integration error messages
            Route::get('/errors', [LfmController::class, 'getErrors'])->name('errors');

            // upload
            Route::any('/upload', [UploadController::class, 'upload'])->name('upload');

            // list images & files
            Route::get('/jsonitems', [ItemsController::class, 'getItems'])->name('items');

            Route::get('/move', [ItemsController::class, 'move'])->name('move');

            Route::get('/domove', [ItemsController::class, 'domove'])->name('do-move');

            // folders
            Route::get('/newfolder', [FolderController::class, 'getAddfolder'])->name('add-folder');

            // list folders
            Route::get('/folders', [FolderController::class, 'getFolders'])->name('folder');

            // crop
            Route::get('/crop', [CropController::class, 'getCrop'])->name('crop');

            Route::get('/cropimage', [CropController::class, 'getCropimage'])->name('crop-image');

            Route::get('/cropnewimage', [CropController::class, 'getNewCropimage'])->name('new-crop-image');

            // rename
            Route::get('/rename', [RenameController::class, 'getRename'])->name('rename');

            // scale/resize
            Route::get('/resize', [ResizeController::class, 'getResize'])->name('resize');

            Route::get('/doresize', [ResizeController::class, 'performResize'])->name('do-resize');

            // download
            Route::get('/download', [DownloadController::class, 'getDownload'])->name('download');

            // delete
            Route::get('/delete', [DeleteController::class, 'getDelete'])->name('delete');

            // Route::get('/demo', 'DemoController@index');
        });
    }
}
