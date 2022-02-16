<?php

namespace App\Console\Commands;

use Laka\Core\Support\Stub;
use Illuminate\Console\Command;
use Illuminate\Contracts\Filesystem\FileExistsException;

abstract class BaseCommand extends Command
{
    /**
     * The name of 'name' argument.
     *
     * @var string
     */
    protected $argumentName = '';

    /**
     * Stub Path
     *
     * @var string
     */
    protected $stubPath;

    /**
     * Get template contents.
     *
     * @return string
     */
    abstract protected function getTemplateContents();

    /**
     * Get the destination file path.
     *
     * @return string
     */
    abstract protected function getDestinationFilePath();

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $path = str_replace('\\', '/', $this->getDestinationFilePath());

        $this->makeIsDirectory($path.DIRECTORY_SEPARATOR.$this->getFileName());

        // Stub::setBasePath($this->getStubPath());

        $tempaleStub = $this->getTemplateContents();

        $this->fileGenerate($path, $this->getFileName(), $tempaleStub);
    }

    protected function makeIsDirectory($path)
    {
        if (!$this->laravel['files']->isDirectory($dir = dirname($path))) {
            $this->laravel['files']->makeDirectory($dir, 0777, true);
        }
    }

    /**
     * File generate
     */
    public function fileGenerate($path, $file_name, $tempaleStub)
    {
        $fullPath = $path.DIRECTORY_SEPARATOR.$file_name;
        try {
            if (file_exists($fullPath)) {
                throw new FileExistsException();
            }

            $tempaleStub->render();
            $tempaleStub->saveTo($path, $file_name);

            $this->info("Created : {$fullPath}");
        } catch (FileExistsException $e) {
            $this->error("File : {$fullPath} already exists.");
        }

    }

    protected function getArgumentName()
    {
        return ucfirst($this->argument($this->argumentName));
    }

    protected function getFileName()
    {
        return $this->getArgumentName().'.php';
    }

    protected function setStubPath($path)
    {
        $this->stubPath = $path;
    }

    protected function getStubPath()
    {
        return base_path($this->stubPath ?? 'app/Console/Commands/Stubs');
    }

    protected function getFileStub($fileName)
    {
        return DIRECTORY_SEPARATOR . $fileName . '.stub';
    }
}
