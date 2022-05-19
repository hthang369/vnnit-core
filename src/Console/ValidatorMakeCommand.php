<?php

namespace Vnnit\Core\Console;

class ValidatorMakeCommand extends BaseCommand
{
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
     * @var string
     */
    protected $className = 'Validator';

    /**
     * @var string
     */
    protected $generatorName = 'validator';

    /**
     * Get the stub file name based on the plain option
     * @return string
     */
    protected function getStubName()
    {
        return '/validators/validator.stub';
    }
}
