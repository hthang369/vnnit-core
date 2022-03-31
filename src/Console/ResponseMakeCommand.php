<?php

namespace Vnnit\Core\Console;

use Nwidart\Modules\Support\Config\GenerateConfigReader;
use Nwidart\Modules\Support\Stub;
use Nwidart\Modules\Traits\ModuleCommandTrait;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class ResponseMakeCommand extends BaseGeneratorCommand
{
    use ModuleCommandTrait;

    /**
     * The name of argument being used.
     *
     * @var string
     */
    protected $argumentName = 'name';

    /**
     * Single or multi file stubs need generate.
     *
     * @var []
     */
    protected $multiFiles = [];

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'module:make-response';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate new response for the specified module.';

    /**
     * Get response name.
     *
     * @return string
     */
    public function getDestinationFilePath()
    {
        $path = $this->laravel['modules']->getModulePath($this->getModuleName());

        $responsePath = GenerateConfigReader::read('responses');

        return $path . $responsePath->getPath() . '/' . $this->getResponseName() . '.php';
    }

    /**
     * @return string
     */
    protected function getTemplateContents()
    {
        $module = $this->laravel['modules']->findOrFail($this->getModuleName());

        return (new Stub($this->getStubName(), [
            'MODULENAME'        => $module->getStudlyName(),
            'CLASSNAME'         => $this->getResponseNameWithoutNamespace(),
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
            ['name', InputArgument::OPTIONAL, 'The name of the response class.'],
            ['module', InputArgument::OPTIONAL, 'The name of module will be used.'],
        ];
    }

    /**
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['plain', 'p', InputOption::VALUE_NONE, 'Generate a plain response', null],
        ];
    }

    /**
     * @return array|string
     */
    protected function getResponseName($file_name=null)
    {
        return $this->getClassFileName();
    }

    /**
     * @return array|string
     */
    private function getResponseNameWithoutNamespace()
    {
        return class_basename($this->getResponseName());
    }

    public function getDefaultNamespace() : string
    {
        return $this->laravel['modules']->config('paths.generator.responses.path', 'Responses');
    }

    /**
     * Get the stub file name based on the plain option
     * @return string
     */
    private function getStubName()
    {
        return '/responses/Response.stub';
    }
}
