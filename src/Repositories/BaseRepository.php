<?php

namespace Vnnit\Core\Repositories;

use Vnnit\Core\Contracts\RepositoryInterface;
use Vnnit\Core\Exceptions\RepositoryException;
use Vnnit\Core\Repositories\FilterQueryString\FilterQueryString;
use Vnnit\Core\Repositories\FilterQueryString\Filters\OrderbyClause;
use Vnnit\Core\Traits\RequestParamTrait;
use Closure;
use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

abstract class BaseRepository implements RepositoryInterface
{
    use FilterQueryString, RequestParamTrait;

    /**
     * @var string
     */
    protected $modelClass;
    /**
     * @var Model
     */
    protected $model = null;

    /**
     * @var Closure
     */
    protected $scopeQuery = null;

    /**
     * @var int
     */
    protected $perPage;

    /**
     * @var array
     */
    protected $select;

    private $baseFilters = [
        'sort' => OrderbyClause::class
    ];

    protected $filters = [];

    /**
     * The array of booted models.
     *
     * @var array
     */
    protected $traitBoots = [];

    /**
     * The array of except query.
     *
     * @var array
     */
    protected $except = [];

    public function __construct()
    {
        $this->makeModel();
        $this->bootTraits();
        $this->initializeTraits();
    }

    protected function bootTraits()
    {
        foreach (class_uses_recursive($this) as $trait) {
            $method = 'boot'.class_basename($trait);
            if (method_exists($this, $method)) {
                $this->traitBoots[] = $method;
            }

            if (method_exists($this, $method = 'initialize'.class_basename($trait))) {
                $this->traitBoots[] = $method;
            }
        }
    }

    protected function initializeTraits()
    {
        foreach(array_unique($this->traitBoots) as $method) {
            $this->{$method}();
        }
    }

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return $this->modelClass;
    }

    public function makeModel()
    {
        $model = resolve($this->model());

        if (!$model instanceof Model) {
            throw new RepositoryException("Class {$this->model()} must be an instance of Illuminate\\Database\\Eloquent\\Model");
        }

        return $this->model = $model;
    }

    public function query()
    {
        return $this->makeModel()->newQuery();
    }

    public function get($columns = ['*'])
    {
        return $this->all($columns);
    }

    /**
     * Get Column by select
     *
     * @return array
     */
    protected function getColumns($columns)
    {
        $model = $this->model instanceof Builder ? $this->model->getModel() : $this->model;

        $mutatorColumns = $model->getMutatedAttributes();

        $listColumn = count($columns) == 0 ? $this->select : $columns;

        return array_merge(array_diff($listColumn, $mutatorColumns), array_map(function($item) {
            return DB::raw("NULL AS $item");
        }, $mutatorColumns));
    }

    /**
     * Retrieve data array for populate field select
     * Compatible with Laravel 5.3
     * @param string $column
     * @param string|null $key
     *
     * @return \Illuminate\Support\Collection|array
     */
    public function pluck($column, $key = null)
    {
        $this->applyCriteria();

        $results = $this->model->pluck($column, $key);

        $this->resetQuery();

        return $this->parserResult($results);
    }

    /**
     * Retrieve all data of repository
     *
     * @param array $columns
     *
     * @return Collection|Model[]
     * @throws Exception
     */
    public function all($columns = [])
    {
        $this->applyScope();
        $this->applyCriteria();

        $columns = $this->getColumns($columns);

        if ($this->model instanceof Builder) {
            $results = $this->model->get($columns);
        } else {
            $results = $this->model->all($columns);
        }

        $this->resetQuery();

        return $this->parserResult($results);
    }

    /**
     *  Retrieve all data of repository, paginated
     *
     * @param null $limit
     * @param array $columns
     * @return mixed
     * @throws Exception
     */
    public function paginate($limit = null, $columns = [], $method = "paginate")
    {
        $this->applyScope();
        $this->applyCriteria();

        $columns = $this->getColumns($columns);

        $limit = is_null($limit) ? $this->getLimitForPagination() : $limit;
        $results = $this->model->{$method}($limit, $columns);
        $results->appends(request()->except($this->except));
        $this->resetQuery();

        return $this->parserResult($results);
    }

    /**
     * @param $id
     * @param array $columns
     * @return mixed
     * @throws Exception
     */
    public function show($id, $columns = [])
    {
        $this->applyScope();
        $this->applyCriteria();

        $columns = $this->getColumns($columns);

        return $this->find($id, $columns);
    }

    final public function find($id, $columns = ['*'])
    {
        $model = $this->model->findOrFail($id, $columns);
        $this->resetQuery();

        return $this->parserResult($model);
    }

    /**
     * @param $field
     * @param $value
     * @param array $columns
     * @return mixed
     * @throws Exception
     */
    public function findByField($field, $value, $columns = [])
    {
        $this->applyScope();
        $this->applyCriteria();

        $columns = $this->getColumns($columns);

        $model = $this->model->where($field, '=', $value)->get($columns);

        $this->resetQuery();

        return $this->parserResult($model);
    }

    /**
     * Find data by multiple fields
     *
     * @param array $where
     * @param array $columns
     *
     * @return mixed
     * @throws Exception
     */
    public function findWhere(array $where, $columns = [])
    {
        $this->applyScope();
        $this->applyCriteria();

        $columns = $this->getColumns($columns);

        $this->applyConditions($where);

        $model = $this->model->get($columns);
        $this->resetQuery();

        return $this->parserResult($model);
    }

    protected function getLimitForPagination()
    {
        if (request()->has('perPage')) {
            $limit = request()->query('perPage');
            return $limit != -1 ? $limit : config('constants.pagination.items_per_page');
        } else  {
            return config('constants.pagination.items_per_page');
        }
    }

    public function first($columns = ['*'])
    {
        $results = $this->model->first($columns);

        $this->resetQuery();

        return $this->parserResult($results);
    }

    public function firstOrNew(array $attributes = [])
    {
        $model = $this->model->firstOrNew($attributes);

        $this->resetQuery();

        return $this->parserResult($model);
    }

    public function firstOrCreate(array $attributes = [])
    {
        $model = $this->model->firstOrCreate($attributes);

        $this->resetQuery();

        return $this->parserResult($model);
    }

    /**
     * Delete multiple entities by given criteria.
     *
     * @param array $where
     *
     * @return int
     * @throws Exception
     */
    public function deleteWhere(array $where)
    {
        $this->applyConditions($where);

        $deleted = $this->model->delete();

        $this->resetQuery();

        return $deleted;
    }

    public function create(array $attributes)
    {
        $model = $this->model->newInstance($attributes);
        $model->save();
        $this->resetQuery();

        return $this->parserResult($model);
    }

    public function update(array $attributes, $id)
    {
        $model = $this->model->findOrFail($id);

        $model->fill($attributes);
        $model->save();

        $this->resetQuery();

        return $this->parserResult($model);
    }

    public function updateOrCreate(array $attributes, array $values = [])
    {
        $model = $this->model->updateOrCreate($attributes, $values);

        $this->resetQuery();

        return $this->parserResult($model);
    }

    public function delete($id)
    {
        $model = $this->model->findOrFail($id);

        $this->resetQuery();

        $deleted = $model->delete();

        return $deleted;
    }

    /**
     * Query Scope
     *
     * @param Closure $scope
     *
     * @return $this
     */
    public function scopeQuery(Closure $scope)
    {
        $this->scopeQuery = $scope;

        return $this;
    }

    protected function parserResult($result)
    {
        if ($result instanceof LengthAwarePaginator) {
            return [
                'rows' => $result->items(),
                'total' => $result->total(),
            ];
        }
        return $result;
    }

    public function resetModel()
    {
        $this->makeModel();
    }

    protected function resetQuery()
    {
        $this->resetModel();
        $this->resetScope();
    }
}
