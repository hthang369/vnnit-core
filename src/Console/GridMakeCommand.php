<?php

namespace Vnnit\Core\Console;

class GridMakeCommand extends BaseCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'module:make-grid';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate new validator for the specified module.';

    /**
     * @var string
     */
    protected $className = 'Grid';

    /**
     * @var string
     */
    protected $generatorName = 'grid';

    /**
     * Get the stub file name based on the plain option
     * @return string
     */
    protected function getStubName()
    {
        return '/grids/grid.stub';
    }
}
