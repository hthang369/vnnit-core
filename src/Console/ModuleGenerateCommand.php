<?php

namespace Vnnit\Core\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ModuleGenerateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module:generate {name} {module}';

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
        $this->call('module:make-core-controller', [
            'name' => $name,
            'module' => $module
        ]);

        $this->call('module:make-repository', [
            'name' => $name,
            'module' => $module
        ]);

        $this->call('module:make-validator', [
            'name' => $name,
            'module' => $module
        ]);

        $this->call('module:make-entity', [
            'name' => $name,
            'module' => $module
        ]);
    }
}
