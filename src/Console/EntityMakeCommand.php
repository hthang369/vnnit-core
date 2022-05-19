<?php

namespace Vnnit\Core\Console;

use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputOption;

class EntityMakeCommand extends BaseCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'module:make-entity';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate new entity for the specified module.';

    /**
     * @var string
     */
    protected $className = 'Model';

    /**
     * @var string
     */
    protected $generatorName = 'model';

    public function handle() : int
    {
        if (parent::handle() === E_ERROR) {
            return E_ERROR;
        }

        $this->handleOptionalMigrationOption();

        return 0;
    }

    /**
     * @return array
     */
    protected function getParameters($module)
    {
        return array_merge(parent::getParameters($module), [
            'LOWER_NAME' => strtolower($this->getArgumentName()),
            'FILLABLE' => $this->getFillable()
        ]);
    }

    /**
     * Create a proper migration name:
     * ProductDetail: product_details
     * Product: products
     * @return string
     */
    private function createMigrationName()
    {
        $pieces = preg_split('/(?=[A-Z])/', $this->argument($this->argumentName), -1, PREG_SPLIT_NO_EMPTY);

        $string = '';
        foreach ($pieces as $i => $piece) {
            if ($i+1 < count($pieces)) {
                $string .= strtolower($piece) . '_';
            } else {
                $string .= Str::plural(strtolower($piece));
            }
        }

        return $string;
    }

    /**
     * Create the migration file with the given model if migration flag was used
     */
    private function handleOptionalMigrationOption()
    {
        if ($this->option('migration') === true) {
            $migrationName = 'create_' . $this->createMigrationName() . '_table';
            $this->call('module:make-migration', ['name' => $migrationName, 'module' => $this->argument('module')]);
        }
    }

    /**
     * @return string
     */
    private function getFillable()
    {
        $fillable = $this->option('fillable');

        if (!is_null($fillable)) {
            $arrays = explode(',', $fillable);

            return json_encode($arrays);
        }

        return '[]';
    }

    /**
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['fillable', null, InputOption::VALUE_OPTIONAL, 'The fillable attributes.', null],
            ['migration', 'm', InputOption::VALUE_NONE, 'Flag to create associated migrations', null],
        ];
    }

    /**
     * Get the stub file name based on the plain option
     * @return string
     */
    protected function getStubName()
    {
        return '/entities/model.stub';
    }
}
