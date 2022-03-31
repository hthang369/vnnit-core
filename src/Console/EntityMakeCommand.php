<?php

namespace Vnnit\Core\Console;

use Nwidart\Modules\Support\Config\GenerateConfigReader;
use Nwidart\Modules\Support\Stub;
use Nwidart\Modules\Traits\ModuleCommandTrait;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class EntityMakeCommand extends BaseGeneratorCommand
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
    protected $name = 'module:make-entity';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate new entity for the specified module.';

    /**
     * Get validator name.
     *
     * @return string
     */
    public function getDestinationFilePath($file_name = null)
    {
        $path = $this->laravel['modules']->getModulePath($this->getModuleName());

        $validatorPath = GenerateConfigReader::read('model');

        return $path . $validatorPath->getPath() . '/' . $this->getEntityName($file_name) . 'Model.php';
    }

    /**
     * @return string
     */
    protected function getTemplateContents($file_name = null)
    {
        $module = $this->laravel['modules']->findOrFail($this->getModuleName());

        return (new Stub($this->getStubName(), [
            'CLASSNAME'         => $this->getEntityName(),
            'MODULENAME'        => $this->getModuleName(),
            'LOWER_NAME'        => strtolower($this->getEntityName())
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
            ['name', InputArgument::OPTIONAL, 'The name of the entity class.'],
            ['module', InputArgument::OPTIONAL, 'The name of module will be used.'],
        ];
    }

    /**
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['plain', 'p', InputOption::VALUE_NONE, 'Generate a plain entity', null],
        ];
    }

    /**
     * @return array|string
     */
    protected function getEntityName()
    {
        return $this->getClassFileName();
    }

    public function getDefaultNamespace() : string
    {
        return $this->laravel['modules']->config('paths.generator.model.path', 'Entities');
    }

    /**
     * Get the stub file name based on the plain option
     * @return string
     */
    private function getStubName($file_name = null)
    {
        return '/entities/Model.stub';
    }
}