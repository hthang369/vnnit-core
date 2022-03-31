<?php

namespace Vnnit\Core\Console;

use Nwidart\Modules\Commands\GeneratorCommand;
use Nwidart\Modules\Support\Config\GenerateConfigReader;
use Nwidart\Modules\Support\Stub;
use Nwidart\Modules\Traits\ModuleCommandTrait;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class ValidatorMakeCommand extends GeneratorCommand
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
    public function getDestinationFilePath($file_name = null)
    {
        $path = $this->laravel['modules']->getModulePath($this->getModuleName());

        $validatorPath = GenerateConfigReader::read('validator');

        return $path . $validatorPath->getPath() . '/' . $this->getValidatorName($file_name) . 'Validator.php';
    }

    /**
     * @return string
     */
    protected function getTemplateContents($file_name = null)
    {
        $module = $this->laravel['modules']->findOrFail($this->getModuleName());

        return (new Stub($this->getStubName(), [
            'CLASSNAME'         => $this->getValidatorName(),
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
        $validator = empty($file_name) ? studly_case($this->argument('name')) : $file_name;

        // if (empty($file_name) && str_contains(strtolower($validator), 'validator') === false) {
        //     $validator .= 'Validator';
        // }

        return $validator;
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
