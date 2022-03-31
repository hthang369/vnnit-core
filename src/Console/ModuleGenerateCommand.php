<?php

namespace Vnnit\Core\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Nwidart\Modules\Contracts\ActivatorInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Vnnit\Core\Generators\ModuleGenerator;

class ModuleGenerateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'module:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear all permissions and reload from config';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $module = $this->argument('module');
        $name = $this->argument('name');

        $moduleName = $module ?? $name;
        $code = with(new ModuleGenerator($moduleName))
            ->setFilesystem($this->laravel['files'])
            ->setModule($this->laravel['modules'])
            ->setConfig($this->laravel['config'])
            ->setActivator($this->laravel[ActivatorInterface::class])
            ->setConsole($this)
            ->setForce($this->option('force'))
            ->setType($this->getModuleType())
            ->setActive(!$this->option('disabled'))
            ->setCheckExists(is_null($module))
            ->setModuleName($moduleName)
            ->generate();

        return $code;
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['name', InputArgument::REQUIRED, 'The names of modules will be created.'],
            ['module', InputArgument::OPTIONAL, 'The module name is already exists.'],
        ];
    }

    protected function getOptions()
    {
        return [
            ['plain', 'p', InputOption::VALUE_NONE, 'Generate a plain module (without some resources).'],
            ['api', null, InputOption::VALUE_NONE, 'Generate an api module.'],
            ['web', null, InputOption::VALUE_NONE, 'Generate a web module.'],
            ['disabled', 'd', InputOption::VALUE_NONE, 'Do not enable the module at creation.'],
            ['force', null, InputOption::VALUE_NONE, 'Force the operation to run when the module already exists.'],
        ];
    }

    /**
    * Get module type .
    *
    * @return string
    */
    private function getModuleType()
    {
        $isPlain = $this->option('plain');
        $isApi = $this->option('api');

        if ($isPlain && $isApi) {
            return 'web';
        }
        if ($isPlain) {
            return 'plain';
        } elseif ($isApi) {
            return 'api';
        } else {
            return 'web';
        }
    }
}
