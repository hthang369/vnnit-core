<?php

namespace Vnnit\Core\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Vnnit\Core\Emails\EmailService;
use Vnnit\Core\Support\Carbon;
use Vnnit\Setting\Facade\Setting;
use Prettus\Validator\Exceptions\ValidatorException;
use Laka\Core\Http\Controllers\BaseController as LakaBaseController;
use Laka\Core\Repositories\BaseRepository;
use Laka\Core\Validators\BaseValidator;
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
     * @param $job
     */
    public function pushJob($job)
    {
        $this->jobs[] = $job;
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
    protected function responseView(Request $request, $data, $viewName = '')
    {
        return $this->response->data($request, $data, $viewName);
    }

    /**
     * @param Request $request
     * @param $data
     * @param $viewName
     */
    protected function responseAction(Request $request, $result, $action, $viewName = '')
    {
        return $this->response->{$action}($request, data_get($result, 'data'), $viewName);
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
     * Export Data
     * @param Request $request
     * @return void
     */
    public function exportData(Request $request, $callback)
    {
        // Check user call back
        if (is_null($callback)) return;

        // Check url for download
        if (!$request->has('url')) {
            throw new \Exception('Don\'t have url');
        }

        if (filter_var($request->url, FILTER_VALIDATE_URL)) {
            //        if (!preg_match('/^(.+)\/$/',$request->url,$match)) {
            throw new \Exception('Wrong url.');
        }
        $url = $request->url;

        // Get url front end
        $getUrlFrontend = rtrim(env('APP_URL_FRONTEND'), '/');

        // todo: get data from db
        $dataExport = [];

        if (is_callable($callback)) {
            $dataExport = call_user_func($callback, $request);
        }

        if (empty($dataExport)) return;

        // todo: handle array data
        $file = $dataExport['file_name'];

        // todo: save array data to file
        $pathFile = Storage::disk('public')->url(config(sprintf('filesystems.disks.public.%s', $this->getPathExport()))) . '/' . $file;

        // todo: download file
        if (file_exists(config(sprintf('filesystems.disks.public.path_%s', $this->getPathExport())) . '/' . $file)) {
            $textEncode = $dataExport['text_encode'];

            /** @var string $download |get in setting value:  1: send email; 0: directly download */
            if (!is_null($download = Setting::get('general', 'download_csv')) && $download) {

                //<editor-fold desc="Set path file, view and send email">
                $emailService = new EmailService();
                $emailService->pathFile = $getUrlFrontend . "{$url}?hash_download={$textEncode}";
                $emailService->view = 'core::email.export_mail';
                //<editor-fold desc="Translate subject">
                $subject                = trans('email.subject_download_month');
                $emailService->subject  = $subject ? $subject : '';
                //</editor-fold>
                $emailService->send();
                //</editor-fold>
            }

            return $this->response->data([
                'file_name'   => $file,
                'file_path'   => $pathFile,
                'is_download' => $download
            ]);
        } else {
            return $this->response->error('Fail', trans('common.file_not_found'));
        }
    }

    /**
     * Dispatch Job
     * @throws ValidatorException
     */
    public function dispatchJobs()
    {
        foreach ($this->jobs as $job) {
            dispatch($job);
        }
    }

    /**
     * Set path export
     *
     * @param string path
     * @return void
     */
    public function setPathExport($path)
    {
        $this->pathExport = $path;
    }

    /**
     * Get path export
     *
     * @return string
     */
    public function getPathExport()
    {
        return $this->pathExport ?? 'export_excel_report';
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
