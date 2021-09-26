<?php
namespace Vnnit\Core\Traits;

trait Exportable
{
    /**
     * @param $job
     */
    public function pushJob($job)
    {
        $this->jobs[] = $job;
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
        return $this->pathExport ?? config('vnnit-core.export_data');
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
}
