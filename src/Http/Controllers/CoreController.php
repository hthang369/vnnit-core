<?php

namespace Vnnit\Core\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Kris\LaravelFormBuilder\FormBuilder;
use Vnnit\Core\Validators\BaseValidator;
use Leantony\Grid\Grid;
use Vnnit\Core\Repositories\BaseRepository;
use Vnnit\Core\Responses\BaseResponse;

class CoreController extends BaseController
{
    private $routeName;
    protected $formBuilder;

    public function __construct(BaseRepository $repository, BaseValidator $validator, BaseResponse $response)
    {
        parent::__construct($repository, $validator, $response);
        $this->formBuilder = resolve(FormBuilder::class);
    }

    public function setDefaultView($value)
    {
        $this->defaultName = $value;
    }

    public function setPathView($value)
    {
        $this->setViewName($value);
    }

    public function setRouteName($value)
    {
        $this->routeName = $value;
    }

    public function renderView($dataGrid, $viewName, $customName = null, $data = [])
    {
        $defaultName = $customName ?? $this->getViewName($viewName);
        // if ($dataGrid instanceof Grid)
            return $dataGrid->renderOn($defaultName, $data);
        // return parent::responseView(request(), $data, $defaultName);
    }

    public function renderViewData($data, $viewName, $customName = null)
    {
        $defaultName = $customName ?? $this->getViewName($viewName);
        return view($defaultName, $data)->render();
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
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
