<?php

namespace Vnnit\Core\Console;

use Nwidart\Modules\Support\Config\GenerateConfigReader;
use Nwidart\Modules\Support\Stub;
use Symfony\Component\Console\Input\InputArgument;

abstract class BaseCommand extends BaseGeneratorCommand
{
    /**
     * @var string
     */
    protected $generatorName;

    /**
     * Get controller name.
     *
     * @return string
     */
    public function getDestinationFilePath()
    {
        $path = $this->getDestinationPath();

        $controllerPath = GenerateConfigReader::read($this->generatorName);

        return $path . $controllerPath->getPath() . '/' . $this->getClassFileName() . '.php';
    }

    /**
     * @return string
     */
    protected function getTemplateContents()
    {
        $module = $this->laravel['modules']->findOrFail($this->getModuleName());

        return (new Stub($this->getStubName(), $this->getParameters($module)))->render();
    }

    /**
     * @return array
     */
    protected function getParameters($module)
    {
        return [
            'MODULE_NAMESPACE'   => $this->getClassNamespace($module),
            'CLASS'             => $this->getClassNameWithoutNamespace(),
            'CLASSNAME'        => $this->getArgumentName(),
        ];
    }

    abstract protected function getStubName();

    protected function getClassNameWithoutNamespace()
    {
        return class_basename($this->getClassFileName());
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['name', InputArgument::OPTIONAL, 'The name of the validator class.'],
            ['module', InputArgument::OPTIONAL, 'The name of module will be used.'],
        ];
    }
}
