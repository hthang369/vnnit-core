<?php

namespace Vnnit\Core\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Vnnit\Core\Emails\EmailService;
use Vnnit\Core\Support\Carbon;
use Vnnit\Setting\Facade\Setting;
use Prettus\Validator\Exceptions\ValidatorException;
use Vnnit\Core\Http\Controllers\BaseController as LakaBaseController;
use Vnnit\Core\Repositories\BaseRepository;
use Vnnit\Core\Validators\BaseValidator;
use Vnnit\Core\Responses\BaseResponse;

/**
 * Class BasesController.
 *
 * @package namespace Vnnit\Core\Http\Controllers;
 */
abstract class BaseController extends LakaBaseController
{
    /**
     * @var BaseResponse
     */
    protected $response;

    /**
     * @var array
     */
    protected $jobs = [];

    /**
     * @var string
     */
    protected $pathExport;

    /**
     * @var bool
     */
    protected $throwException;

    protected $redirectRoute = [
        'error' => ''
    ];

    /**
     * BasesController constructor.
     *
     * @param BaseRepository $repository
     * @param BaseValidator $validator
     * @param BaseResponse $response
     * @param CriteriaInterface $criteria
     */
    public function __construct(BaseRepository $repository, BaseValidator $validator, BaseResponse $response)
    {
        $this->repository = $repository;
        $this->response = $response;
        $this->throwException = false;
        parent::__construct($validator);
    }



    /**
     * @param $isThrow
     */
    public function setThrowException($isThrow)
    {
        $this->throwException = $isThrow;
    }



    /**
     * @param Request $request
     * @param $data
     * @param $viewName
     */
    protected function responseErrorAction(Request $request, \Exception $e, $viewName = '')
    {
        $message = $e->getMessage();
        if ($e instanceof ValidatorException) {
            return $message = $e->getMessageBag();
        }

        if ($this->throwException)
            throw $e;
        else
            return $this->response->error($request, $message, $viewName);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (request()->has('limit')) {
            $bases = $this->repository->paginate(request('limit'));
        } else {
            $bases = $this->repository->all();
        }

        return $this->responseView(request(), $bases, $this->getViewName(__FUNCTION__));
    }







    protected function validateDateRange($dateFrom, $dateTo, $maxMonth, $field = 'date_to')
    {
        $maxReportRangeInMonth = $maxMonth - 1; // Need to minus 1 because diffInMonths() returns +1 to the result
        $carbonFrom = new Carbon($dateFrom);
        $carbonTo = new Carbon($dateTo);

        $diffInMonths = $carbonFrom->diffInMonths($carbonTo);

        if ($diffInMonths > $maxReportRangeInMonth) {
            throw ValidationException::withMessages([$field => str_replace('{0}', $maxReportRangeInMonth + 1, trans('common.validate.reportDateRange'))]);
        }
    }
}
