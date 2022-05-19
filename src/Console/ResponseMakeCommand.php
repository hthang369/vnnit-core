<?php

namespace Vnnit\Core\Console;

class ResponseMakeCommand extends BaseCommand
{
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
     * @var string
     */
    protected $className = 'Response';

    /**
     * @var string
     */
    protected $generatorName = 'response';

    /**
     * Get the stub file name based on the plain option
     * @return string
     */
    protected function getStubName()
    {
        return '/responses/response.stub';
    }
}
