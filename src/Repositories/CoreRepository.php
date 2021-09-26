<?php

namespace Vnnit\Core\Repositories;

use Illuminate\Database\Eloquent\Builder;
use Vnnit\Core\Traits\MakeObjectInstance;
use Vnnit\Core\Traits\PresenterDataGrid;

/**
 * Class BaseRepositoryEloquent.
 *
 * @package namespace Modules\Core\Repositories;
 */
abstract class CoreRepository extends BaseRepository
{
    use MakeObjectInstance, PresenterDataGrid;
    /**
     * $var service;
     */
    protected $service;

    /**
     * $var grid;
     */
    protected $dataGrid;

    /**
     * $var form;
     */
    protected $formData;

    /**
     * Specify Service class name
     *
     * @return string
     */
    public function service()
    {
        return null;
    }

    /**
     * Specify Grid class name
     *
     * @return string
     */
    public function grid()
    {
        return null;
    }

    /**
     * Specify Form class name
     *
     * @return string
     */
    public function form()
    {
        return null;
    }

    /**
     * @param null $service
     *
     * @return mixin
     * @throws RepositoryException
     */
    public function makeService($service = null)
    {
        $serviceClass = $service ?? $this->service();

        $this->service = $this->makeObject($serviceClass);

        return $this->service;
    }

    /**
     * @param null $grid
     *
     * @return mixin
     * @throws RepositoryException
     */
    public function makeDataGrid($grid = null)
    {
        $gridClass = $grid ?? $this->grid();

        $this->dataGrid = $this->makeObject($gridClass);

        return $this->dataGrid;
    }

    /**
     * @param null $form
     *
     * @return mixin
     * @throws RepositoryException
     */
    public function makeFormData($form = null)
    {
        $formClass = $form ?? $this->form();

        $this->formData = $this->makeObject($formClass);

        return $this->formData;
    }

    /**
     * Boot up the repository, pushing criteria
     */
    protected function boot()
    {
        $this->makeService();
        $this->makeDataGrid();
        $this->makeFormData();
    }

    public function unguard()
    {
        $this->model::unguard();
    }

    public function reguard()
    {
        $this->model::reguard();
    }

    public function allDataGrid()
    {
        if ($this->dataGrid) {
            return $this->dataGrid->create($this->getGridParams());
        }
        return null;
    }

    protected function getGridParams()
    {
        return [
            'query' => $this->getQuery(),
            'request' => request()
        ];
    }

    protected function getQuery()
    {
        $this->applyCriteria();
        $this->applyScope();

        if ($this->model instanceof Builder)
            $results = $this->model;
        else
            $results = $this->model::query();

        $this->resetModel();
        $this->resetScope();

        return $results;
    }

    public function formGenerate($route, $actionName, $config = [])
    {
        $modal = [
            'model' => class_basename($this->model()),
            'route' => $route,
            'action' => $actionName,
            'pjaxContainer' => request()->get('ref'),
        ];

        $modal = array_merge($modal, $config);

        return [$modal, $this->form()];
    }

    public function getCreatedUpdatedUser()
    {
        $data[] = $this->model::getUpdatedUser();
        if (!$this->model->exists) array_unshift($data, $this->model::getCreatedUser());
        return array_fill_keys($data, $this->model->getAuthUser());
    }
}
