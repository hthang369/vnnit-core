<?php

namespace Vnnit\Core\Console;

class CoreControllerMakeCommand extends BaseCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'module:make-core-controller';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate new restful controller for the specified module.';

    /**
     * @var string
     */
    protected $className = 'Controller';

    /**
     * @var string
     */
    protected $generatorName = 'controller';

    /**
     * Get the stub file name based on the options
     * @return string
     */
    protected function getStubName()
    {
        return '/controllers/core-controller.stub';
    }
}
