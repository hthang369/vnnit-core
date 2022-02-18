<?php

namespace Vnnit\Core\Presenters;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Vnnit\Core\Contracts\PresenterInterface;
use Vnnit\Core\Grids\GenericButton;
use Vnnit\Core\Helpers\Classes;
use Vnnit\Core\Traits\Common\CommonFunction;
use Vnnit\Core\Traits\Grids\HasDataColumn;
use Vnnit\Core\Traits\Grids\RendersButtons;

abstract class BaseDataGridPresenter implements PresenterInterface
{
    use HasDataColumn, CommonFunction, RendersButtons;

    private $fields = [];
    private $actionName = 'action';
    protected $exceptQuery = [];
    protected $actionColumnOptions = [];
    private $template = [
        'fields'        => [],
        'rows'          => [],
        'total'         => 0,
        'pages'         => 0,
        'currentPage'   => 0,
        'except'        => [],
        'paginator'     => null
    ];
    protected $resultData = [];
    protected $paginator = null;

    protected $id;
    protected $name;

    public function __construct()
    {
        $this->configureButtons();
    }

    /**
    * Configure rendered buttons, or add your own
    *
    * @return void
    */
    protected function configureButtons()
    {
        $this->setButtons();
    }

    protected function setColumns()
    {
        return [];
    }

    private function getActionOptions()
    {
        $prefix = config('vnnit-core.prefix');
        return array_merge(['sortable' => false, 'cell' => "$prefix::tables.buttons.action", 'dataType' => 'buttons'], $this->actionColumnOptions);
    }

    protected function getColumns()
    {
        $fields = collect($this->fields);
        $fields = $fields->merge($this->setColumns());
        $actionColumn = $this->getField($this->actionName, translate('table.action'), $this->getActionOptions());
        $actionColumn['class'] = Classes::get(['table-action', $actionColumn['class']]);
        $fields->push($actionColumn);

        return $fields->all();
    }

    private function parseRows($data)
    {
        return collect($data->items())->map(function(&$item, $key) use($data) {
            if (method_exists($this, 'customizeRowData')) {
                $itemData = ['from' => $data->firstItem(), 'to' => $data->lastItem(), 'data' => $item, 'index' => $key];
                $item = data_get(call_user_func([$this, 'customizeRowData'], $itemData), 'data');
            }
            if (blank($item->{$this->actionName})) {
                $actions = $this->getButtons(GenericButton::TYPE_ROW);
                data_set($item, $this->actionName, $actions);
            }
            return $item;
        })->toArray();
    }

    /**
     * Prepare data to present
     *
     * @param $data
     *
     * @return mixed
     */
    public function present($results)
    {
        if ($results instanceof LengthAwarePaginator) {
            $this->paginator = $results;
            return $this->resultData = $this->parsePresent($results, $results->total());
        }
        return $results;
    }

    protected function parsePresent($results, $total)
    {
        $this->setDefaultButtonsToGenerate();
        return array_merge($this->template, [
            'fields'        => $this->getColumns(),
            'rows'          => method_exists($results, 'items') ? $this->parseRows($results) : $results,
            'total'         => $total,
            'pages'         => method_exists($results, 'lastPage') ? $results->lastPage() : 0,
            'currentPage'   => method_exists($results, 'currentPage') ? $results->currentPage() : 0,
            'except'        => $this->exceptQuery,
            'paginator'     => $results
        ]);
    }

    protected function editToolbarButton($key, $options)
    {
        $this->editButton($key, $options, GenericButton::TYPE_TOOLBAR);
    }

    protected function editRowButton($key, $options)
    {
        $this->editButton($key, $options, GenericButton::TYPE_ROW);
    }

    private function editButton($key, $options, $type)
    {
        $button = $this->getButton($key, $type);
        if (!is_object($button)) return;
        $refector = new \ReflectionClass(get_class($button));
        $attrs = $refector->getProperties(\ReflectionProperty::IS_PUBLIC | \ReflectionProperty::IS_PROTECTED);
        array_walk($attrs, function($item) use(&$button, $options) {
            $keyName = $item->getName();
            if (in_array($keyName, array_keys($options))) {
                $button->{$keyName} = $options[$keyName];
            }
        });
        $methods = $refector->getMethods(\ReflectionMethod::IS_PUBLIC);
        array_walk($methods, function($method) use(&$button, $options) {
            $keyName = $method->getName();
            if (in_array($keyName, array_keys($options))) {
                $button->$keyName($options[$keyName]);
            }
        });
    }

    protected function addToolbarButton($key, $options)
    {
        $this->addButton($key, $options, GenericButton::TYPE_TOOLBAR);
    }

    protected function addRowButton($key, $options)
    {
        $this->addButton($key, $options, GenericButton::TYPE_ROW);
    }

    private function addButton($key, $options, $type)
    {
        if (!$this->hasButton($key, $type)) {
            $button = GenericButton::make($options);
            data_set($this->buttons, "{$type}.{$key}", $button);
        }
    }
}
