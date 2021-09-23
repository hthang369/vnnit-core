<?php

namespace Vnnit\Core\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Kris\LaravelFormBuilder\FormBuilder;
use Laka\Core\Repositories\BaseRepository;
use Laka\Core\Validators\BaseValidator;
use Vnnit\Core\Responses\BaseResponse;

class CoreController extends BaseController
{
    private $defaultView;
    private $pathView;
    private $routeName;
    protected $formBuilder;

    public function __construct(BaseRepository $repository, BaseValidator $validator, BaseResponse $response)
    {
        parent::__construct($repository, $validator, $response);
        $this->formBuilder = resolve(FormBuilder::class);
    }

    public function setDefaultView($value)
    {
        $this->defaultView = $value;
    }

    public function setPathView($value)
    {
        $this->pathView = $value;
    }

    public function setRouteName($value)
    {
        $this->routeName = $value;
    }

    public function renderView($dataGrid, $viewName, $customName = null, $data = [])
    {
        $defaultName = $customName ?? data_get($this->pathView, $viewName, $this->defaultView . '.' . $viewName);
        return $dataGrid->renderOn($defaultName, $data);
    }

    public function renderViewData($data, $viewName, $customName = null)
    {
        $defaultName = $customName ?? data_get($this->pathView, $viewName, $this->defaultView . '.' . $viewName);
        return view($defaultName, $data)->render();
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        foreach ($this->defaultCriteria as $criteria) {
            $this->repository->pushCriteria($criteria);
        }
        $bases = $this->repository->allDataGrid();
        return $this->renderView($bases, __FUNCTION__);
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        list($modal, $formData) = $this->repository->formGenerate(route($this->routeName.'.store'), __FUNCTION__);

        $form = $this->formBuilder->create($formData)->renderForm([], false, true, false);

        return $this->renderViewData(compact('modal', 'form'), __FUNCTION__);
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        $base = $this->repository->find($id);

        list($modal, $formData) = $this->repository->formGenerate(route($this->routeName.'.update', data_get($base, 'id', $id)), 'update', ['method' => 'patch']);

        $form = $this->formBuilder->create($formData, [
            'model' => $base
        ])->renderForm([], false, true, false);

        return $this->renderViewData(compact('modal', 'form'), __FUNCTION__);
    }
}
