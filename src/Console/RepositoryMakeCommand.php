<?php

namespace Vnnit\Core\Console;

use Nwidart\Modules\Commands\GeneratorCommand;
use Nwidart\Modules\Support\Config\GenerateConfigReader;
use Nwidart\Modules\Support\Stub;
use Nwidart\Modules\Traits\ModuleCommandTrait;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class RepositoryMakeCommand extends GeneratorCommand
{
    use ModuleCommandTrait;

    /**
     * The name of argument being used.
     *
     * @var string
     */
    protected $argumentName = 'name';

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'module:make-repository';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate new repository for the specified module.';

    /**
     * Get repository name.
     *
     * @return string
     */
    public function getDestinationFilePath($file_name = null)
    {
        $path = $this->laravel['modules']->getModulePath($this->getModuleName());

        $repositoryPath = GenerateConfigReader::read('repository');

        return $path . $repositoryPath->getPath() . '/' . $this->getRepositoryName($file_name) . 'Repository.php';
    }

    /**
     * @return string
     */
    protected function getTemplateContents($file_name = null)
    {
        $module = $this->laravel['modules']->findOrFail($this->getModuleName());

        return (new Stub($this->getStubName($file_name), [
            'CLASSNAME'         => $this->getRepositoryName(),
            'MODULE'            => $this->getModuleName(),
        ]))->render();
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['name', InputArgument::OPTIONAL, 'The name of the repository class.'],
            ['module', InputArgument::OPTIONAL, 'The name of module will be used.'],
        ];
    }

    /**
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['plain', 'p', InputOption::VALUE_NONE, 'Generate a plain repository', null],
        ];
    }

    /**
     * @return array|string
     */
    protected function getRepositoryName($file_name = null)
    {
        $repository = empty($file_name) ? studly_case($this->argument('name')) : $file_name;

        // if (empty($file_name) && str_contains(strtolower($repository), 'repository') === false) {
        //     $repository .= 'Repository';
        // }

        return $repository;
    }

    public function getDefaultNamespace(): string
    {
        return $this->laravel['modules']->config('paths.generator.repository.path', 'Repositories');
    }

    /**
     * Get the stub file name based on the plain option
     * @return string
     */
    private function getStubName($file_name)
    {
        return '/repositories/Repository.stub';
    }
}
