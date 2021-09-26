<?php

namespace Vnnit\Core\Http\Controllers;

use Vnnit\Core\Contracts\BaseControllerInterface;
use Vnnit\Core\Http\Response\WebResponse;
use Vnnit\Core\Repositories\BaseRepository;
use Vnnit\Core\Support\Factory;
use Vnnit\Core\Traits\Authorizable;
use Vnnit\Core\Validators\BaseValidator;
use Exception;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use Vnnit\Core\Responses\BaseResponse;

abstract class BaseController extends Controller implements BaseControllerInterface
{
    use Authorizable, AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    /**
     * @var BaseRepository
     */
    protected $repository;

    /**
     * @var BaseValidator
     */
    protected $validator;

    /**
     * @var BaseValidator
     */
    protected $response;

    /**
     * @var Factory
     */
    protected $factory;

    protected $defaultName;

    protected $listDefaultViewName = [
        'index'     => '',
        'create'    => '',
        'show'      => '',
        'store'     => '',
        'update'    => '',
        'destroy'   => ''
    ];

    protected $listViewName = [];

    protected $errorRouteName = [];

    protected $messageResponse = [];

    /**
     * BaseController constructor.
     *
     * @param BaseValidator $validator
     */
    public function __construct(BaseRepository $repository, BaseValidator $validator, BaseResponse $response) {
        $this->validator = $validator;
        $this->repository = $repository;
        $this->response = $response;
        $this->factory = new Factory($this);
        $this->setControllerActionPermission($this->permissionActions);
        $this->setViewName($this->listViewName);
    }

    /**
     * Display a listing or the specified resource.
     *
     * @param null $id
     * @return Response|mixed
     * @throws Exception
     */
    public function view($id = null) {
        if ($id) {
            return $this->show($id);
        }
        return $this->index();
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response|mixed
     * @throws Exception
     */
    public function index() {
        $list = $this->repository->paginate();

        return $this->responseView(request(), $list, $this->getViewName(__FUNCTION__), $this->getMessageResponse(__FUNCTION__));
    }

    /**
     * Display the specified resource.
     *
     * @param Request $request
     * @return Response|mixed
     * @throws Exception
     */
    public function create() {
        $data = null;
        return $this->responseView(request(), $data, $this->getViewName(__FUNCTION__), $this->getMessageResponse(__FUNCTION__));
    }

    /**
     * Display the specified resource.
     *
     * @param $id
     * @return Response|mixed
     * @throws Exception
     */
    public function edit($id) {
        $data = $this->repository->show($id);
        return $this->responseView(request(), $this->getViewName(__FUNCTION__), $data, $this->getMessageResponse(__FUNCTION__));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response|mixed
     * @throws ValidatorException
     */
    public function store(Request $request) {
        $this->validator($request->all(), ValidatorInterface::RULE_CREATE);

        $data = $this->repository->create($request->all());

        if (method_exists($data, 'toArray')) {
            $data = $data->toArray();
        }

        return $this->responseAction($request, $data, 'created', route($this->getViewName(__FUNCTION__)), $this->getMessageResponse(__FUNCTION__));
    }

    /**
     * Display the specified resource.
     *
     * @param $id
     * @return Response|mixed
     * @throws Exception
     */
    public function show($id) {
        $data = $this->repository->show($id);
        return $this->responseView($this->getViewName(__FUNCTION__), $data, $this->getMessageResponse(__FUNCTION__));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param $id
     * @return Response|mixed
     * @throws ValidatorException
     */
    public function update(Request $request, $id) {
        $this->validator->setId($id);
        $this->validator($request->all(), ValidatorInterface::RULE_UPDATE);

        $data = $this->repository->update($request->all(), $id);

        if (method_exists($data, 'toArray')) {
            $data = $data->toArray();
        }

        return $this->responseAction($request, $data, 'updated', route($this->getViewName(__FUNCTION__), $id), $this->getMessageResponse(__FUNCTION__));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return Response|mixed
     */
    public function destroy($id) {
        $this->repository->delete($id);

        return $this->response->deleted(request(), route($this->getViewName(__FUNCTION__), $id), $this->getMessageResponse(__FUNCTION__));
    }

    /**
     * @param Request $request
     * @param $id
     * @return mixed|void
     */
    public function import(Request $request, $id)
    {
        // todo: check input file

        // todo: validate format file

        // todo: convert to array

        // todo: handle array

        // todo: save array to db
    }

    /**
     * @param Request $request
     * @param int $id
     * @return array|void
     */
    public function export(Request $request, $id)
    {
        // todo: get data from db

        // todo: handle array data

        // todo: save array data to file

        // todo: download file
    }

    /**
     * @param $data
     * @param $rules
     * @return mixed|void
     * @throws ValidatorException
     */
    public function validator($data, $rules) {
        $this->validator->with($data)->passesOrFail($rules);
    }

    protected function getViewName($key)
    {
        return data_get($this->listDefaultViewName, $key, $this->defaultName.'.'.$key);
    }

    private function getMessageResponse($key)
    {
        return data_get($this->messageResponse, $key, null);
    }

    /**
     * @param Request $request
     * @param $data
     * @param $viewName
     */
    protected function responseView(Request $request, $data, $viewName = '', $message = null)
    {
        return $this->response->data($request, $data, $viewName, $message);
    }

    /**
     * @param Request $request
     * @param $data
     * @param $viewName
     */
    protected function responseAction(Request $request, $data, $action, $viewName = '', $message = null)
    {
        return $this->response->{$action}($request, $data, $viewName, $message);
    }

    /**
     * @param $class
     * @param $alias
     */
    public function setProperty($class, $alias) {
        $this->$alias = $class;
    }

    protected function setViewName($listViewName)
    {
        $this->listDefaultViewName = array_merge($this->listDefaultViewName, $listViewName);
    }
}
