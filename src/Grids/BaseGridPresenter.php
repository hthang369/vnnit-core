<?php

namespace App\Presenters;

use App\Facades\Common;
use Laka\Core\Helpers\Classes;
use Laka\Core\Presenters\BaseDataGridPresenter;
use Laka\Core\Traits\HasDataColumn;

abstract class BaseGridPresenter extends BaseDataGridPresenter
{
    use HasDataColumn;

    private $indexName = 'index';

    protected $indexColumnOptions = [];

    private function getIndexOptions()
    {
        return array_merge(['sortable' => false], $this->indexColumnOptions);
    }

    protected function getColumns()
    {
        $fields = collect();
        $indexColumn = $this->getField($this->indexName, trans('table.index'), $this->getIndexOptions());
        $indexColumn['class'] = Classes::get(['table-index', $indexColumn['class']]);
        $fields->push($indexColumn);
        $fields = $fields->merge(parent::getColumns());

        return $fields->all();
    }

    protected function customizeRowData($itemData)
    {
        $range = range($itemData['from'], $itemData['to']);
        if (blank($itemData['data']->{$this->indexName})) {
            data_set($itemData['data'], $this->indexName, $range[$itemData['index']]);
        }
        if (method_exists($this, 'customItemData')) {
            $itemData['data'] = call_user_func([$this, 'customItemData'], $itemData['data']);
        }
        return $itemData;
    }

    protected function visibleEdit($item)
    {
        return user_can("edit_{$this->getSectionCode()}");
    }

    protected function visibleDetail($item)
    {
        return user_can("view_{$this->getSectionCode()}");
    }

    protected function visibleDelete($item)
    {
        return user_can("delete_{$this->getSectionCode()}");
    }
}
