<?php

namespace Vnnit\Core\Console;

class MakeFilterCommand extends BaseCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'module:make-filter';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate files filter...';

    /**
     * @var string
     */
    protected $className = 'Clause';

    /**
     * @var string
     */
    protected $generatorName = 'repository-filter';

    protected function getClassFileName()
    {
        $name = parent::getClassFileName();

        return "Where{$name}";
    }

    /**
     * Get the stub file name based on the plain option
     * @return string
     */
    protected function getStubName()
    {
        return '/repositories/filters/filterClause.stub';
    }
}
