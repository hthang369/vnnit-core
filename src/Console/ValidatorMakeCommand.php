<?php

namespace Vnnit\Core\Console;

use Nwidart\Modules\Support\Config\GenerateConfigReader;
use Nwidart\Modules\Support\Stub;
use Nwidart\Modules\Traits\ModuleCommandTrait;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class ValidatorMakeCommand extends BaseGeneratorCommand
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
    protected $name = 'module:make-validator';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate new validator for the specified module.';

    /**
     * Get validator name.
     *
     * @return string
     */
    public function getDestinationFilePath()
    {
        $path = $this->laravel['modules']->getModulePath($this->getModuleName());

        $validatorPath = GenerateConfigReader::read('validator');

        return $path . $validatorPath->getPath() . '/' . $this->getValidatorName() . 'Validator.php';
    }

    /**
     * @return string
     */
    protected function getTemplateContents()
    {
        $module = $this->laravel['modules']->findOrFail($this->getModuleName());

        return (new Stub($this->getStubName(), [
            'CLASSNAME'         => $this->getValidatorName(),
            'MODULENAME'        => $this->getModuleName(),
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
            ['name', InputArgument::OPTIONAL, 'The name of the validator class.'],
            ['module', InputArgument::OPTIONAL, 'The name of module will be used.'],
        ];
    }

    /**
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['plain', 'p', InputOption::VALUE_NONE, 'Generate a plain validator', null],
        ];
    }

    /**
     * @return array|string
     */
    protected function getValidatorName($file_name = null)
    {
        return $this->getClassFileName();
    }

    /**
     * @return array|string
     */
    private function getValidatorNameWithoutNamespace()
    {
        return class_basename($this->getValidatorName());
    }

    public function getDefaultNamespace(): string
    {
        return $this->laravel['modules']->config('paths.generator.validator.path', 'Validators');
    }

    /**
     * Get the stub file name based on the plain option
     * @return string
     */
    private function getStubName()
    {
        return '/validators/Validator.stub';
    }
}
