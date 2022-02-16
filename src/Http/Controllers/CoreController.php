<?php

namespace Vnnit\Core\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Vnnit\Core\Validators\BaseValidator;
use Vnnit\Core\Forms\FormBuilder;
use Vnnit\Core\Grids\BaseGridPresenter;
use Vnnit\Core\Repositories\CoreRepository;
use Vnnit\Core\Responses\BaseResponse;

class CoreController extends BaseController
{
    private $routeName;
    protected $formBuilder;

    public function __construct(CoreRepository $repository, BaseValidator $validator, BaseResponse $response)
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

    public function renderView($results, $viewName, $customName = null, $data = [])
    {
        list($grid, $result) = $results;
        $defaultName = $customName ?? $this->getViewName($viewName);
        return parent::responseView(request(), compact('data', 'grid', 'result'), $defaultName);
    }

    public function renderViewData($data, $viewName, $customName = null)
    {
        $defaultName = $customName ?? $this->getViewName($viewName);
        return $this->responseView(request(), $data, $defaultName);
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

        $form = $this->formBuilder->create($formData, $modal)->renderForm([], false, true, false);

        return $this->renderViewData(compact('modal', 'form'), __FUNCTION__);
    }

    /**
     * Display the specified resource.
     *
     * @param $id
     * @return Response|mixed
     * @throws Exception
     */
    public function edit($id) {
        return $this->show($id);
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        $base = $this->repository->find($id);

        list($modal, $formData) = $this->repository->formGenerate(route($this->routeName.'.update', data_get($base, 'id', $id)), 'update', ['method' => 'put', 'model' => $base]);

        $form = $this->formBuilder->create($formData, [
            'model' => $base
        ])->renderForm([], false, true, false);

        return $this->renderViewData(compact('modal', 'form'), __FUNCTION__);
    }
}
