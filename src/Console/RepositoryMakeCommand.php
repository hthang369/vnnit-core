<?php

namespace Vnnit\Core\Console;

class RepositoryMakeCommand extends BaseCommand
{
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
     * @var string
     */
    protected $className = 'Repository';

    /**
     * @var string
     */
    protected $generatorName = 'repository';

    /**
     * Get the stub file name based on the plain option
     * @return string
     */
    protected function getStubName()
    {
        return '/repositories/repository.stub';
    }
}
