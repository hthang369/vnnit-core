<?php

namespace Vnnit\Core\Console;

class FormMakeCommand extends BaseCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'module:make-form';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate new validator for the specified module.';

    /**
     * @var string
     */
    protected $className = 'Form';

    /**
     * @var string
     */
    protected $generatorName = 'form';

    /**
     * Get the stub file name based on the plain option
     * @return string
     */
    protected function getStubName()
    {
        return '/forms/form.stub';
    }
}
