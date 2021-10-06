<?php

namespace Vnnit\Core\Support;

use App\Exceptions\InvalidSpecificationException;
use Carbon\Carbon;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Filesystem\Exception\FileNotFoundException;
use Symfony\Component\HttpFoundation\File\Exception\ExtensionFileException;

class FileManagementService
{
    private $directories;
    private $pathDirectories;
    private $allowExtensions;
    private $storage;

    public function __construct()
    {
        $this->InitConfig();
    }

    private function InitConfig()
    {
        $this->storage = Storage::disk('public');
        $this->directories = config('filesystems.disks.public.download');
        $this->pathDirectories = config('filesystems.disks.public.upload');
        $this->allowExtensions = [
            'images' => ['jpg', 'jpeg', 'gif', 'png'],
            'media' => ['mpeg', 'mp4', 'mov'],
            'file' => [],
        ];
    }

    public function setPathDirectories($path)
    {
        $this->pathDirectories = $path;
    }

    public function setDirectories($path)
    {
        $this->directories = $path;
    }

    private function getDirectories($type = null)
    {
        return is_null($type) ? $this->directories : $this->directories . DIRECTORY_SEPARATOR . $type;
    }

    private function getPathDirectories($type = null)
    {
        return is_null($type) ? $this->pathDirectories : $this->pathDirectories . DIRECTORY_SEPARATOR . $type;
    }

    public function getListFileImages()
    {
        return $this->getListFileTypes('images', 'image');
    }

    public function getListFileMedia()
    {
        return $this->getListFileTypes('media', 'video');
    }

    public function getFileImages($file_name)
    {
        return $this->getFileTypes('images', $file_name);
    }

    public function getFileMedia($file_name)
    {
        return $this->getFileTypes('media', $file_name);
    }

    public function uploadFileImages($files)
    {
        return $this->uploadFilesType($files, 'images');
    }

    public function uploadFileMedia($files)
    {
        return $this->uploadFilesType($files, 'media');
    }

    protected function getFileTypes($type, $file_name)
    {
        $allFiles = $this->storage->allFiles($this->getDirectories($type));
        $files = array_filter($allFiles, function ($file) use ($file_name) {
            return str_is(pathinfo($file, PATHINFO_BASENAME), $file_name);
        });

        return array_first($this->getFileInfo($files));
    }

    protected function getListFileTypes($type, $prefixExtension)
    {
        $allFiles = $this->storage->allFiles($this->getDirectories($type));
        $allFiles = array_values(array_filter($allFiles, function ($file) use ($prefixExtension) {
            return str_is(sprintf('%s/*', $prefixExtension), $this->storage->mimeType($file));
        }));

        return $this->getFileInfo($allFiles);
    }

    protected function uploadFilesType($files, $type)
    {
        $list_files = [];
        if (empty($files)) {
            throw new InvalidSpecificationException(trans('common.file_not_found'));
        }
        foreach ($files as $file) {
            $list_files[] = $this->uploadFileType($file, $type);
        }
        return $list_files;
    }

    protected function uploadFileType(UploadedFile $file, $type)
    {
        if (!$file->isValid()) {
            throw new FileNotFoundException(trans('common.file_not_found'), 409);
        }
        if (!in_array($file->getClientOriginalExtension(), $this->allowExtensions[$type])) {
            throw new ExtensionFileException(trans('common.validate.file_type_mismatch'), 409);
        }
        $date = Carbon::now()->timezone('Asia/Ho_Chi_Minh')->format('YmdHis');
        $file_name = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME). '_'. $date. '.' . $file->getClientOriginalExtension();
        $directory = $this->getPathDirectories($type);
        try {
            $file->move($directory, $file_name);
        } catch (\Exception $ex) {
            throw new InvalidSpecificationException($ex->getMessage());
        }

        return [
            'file_name' => $file_name,
            'path' => $this->getDirectories($type).DIRECTORY_SEPARATOR.$file_name
        ];
    }

    public function deleteFileType($type, $file_name)
    {
        try {
            return $this->storage->delete($this->getDirectories($type).DIRECTORY_SEPARATOR.$file_name);
        } catch (\Exception $ex) {
            throw new InvalidSpecificationException($ex->getMessage());
        }
    }

    private function getFileInfo($files)
    {
        return array_map(function ($file) {
            $metaData = $this->storage->getMetadata($file);
            $metaData['path'] = $this->storage->url($metaData['path']);
            $metaData['type'] = $this->storage->mimeType($file);
            $metaData['file_name'] = pathinfo($file, PATHINFO_BASENAME);
            return $metaData;
        }, $files);
    }
}
