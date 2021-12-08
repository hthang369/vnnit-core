<?php

namespace Vnnit\Core\Plugins\FileManager\Controllers;

use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class FolderController extends LfmController
{
    /**
     * Get list of folders as json to populate treeview.
     *
     * @return mixed
     */
    public function getFolders()
    {
        $folder_types = array_filter(array_keys(config('file-manager.folder_list')), function ($type) {
            return $this->helper->allowFolderType($type);
        });
        // $directory = base_path();
        // $size = 0;
        // foreach(new RecursiveIteratorIterator(new RecursiveDirectoryIterator($directory)) as $file){
        //     $size+=$file->getSize();
        // }
        $total_disk = disk_total_space('/');
        $free_disk = disk_free_space('/');
        $used_disk = $total_disk - $free_disk;
        return $this->view('tree')
            ->with([
                'root_folders' => array_map(function ($type) use ($folder_types) {
                    $path = $this->lfm->dir($this->helper->getRootFolder($type));

                    return (object) [
                        'name' => translate('lfm.title-' . $type),
                        'url' => $path->path('working_dir'),
                        'children' => $path->folders(),
                        'has_next' => ! ($type == end($folder_types)),
                    ];
                }, $folder_types),
                'total_disk' => $total_disk,
                'used_disk' => $used_disk
            ]);
    }

    /**
     * Add a new folder.
     *
     * @return mixed
     */
    public function getAddfolder()
    {
        $folder_name = $this->helper->input('name');

        try {
            if ($folder_name === null || $folder_name == '') {
                return $this->helper->error('folder-name');
            } elseif ($this->lfm->setName($folder_name)->exists()) {
                return $this->helper->error('folder-exist');
            } elseif (config('lfm.alphanumeric_directory') && preg_match('/[^\w-]/i', $folder_name)) {
                return $this->helper->error('folder-alnum');
            } else {
                $this->lfm->setName($folder_name)->createFolder();
            }
        } catch (\Exception $e) {
            return $e->getMessage();
        }

        return parent::$success_response;
    }
}
