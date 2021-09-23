<?php

namespace Vnnit\Core\Emails;

use App\Models\User\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Mockery\Exception;
use VnnitanageOvertimeDayoff\Repositories\EmailNotificationRepository;

class EmailService
{
    public $pathFile;
    public $view;
    public $subject;
    public $content;
    public $title;
    public $email;
    public $name;

    public function __construct()
    {

    }

    /**
     * Send
     *
     * @return void
     * @throws \Exception
     * @author thanh_tuan
     * @since  2019-03-14
     */
    public function send()
    {
        //<editor-fold desc="Check subject">
        $subject = $this->subject;
        if (!isset($subject) || empty($subject)) {
            throw new \Exception('Don\'t have subject');
        }
        //</editor-fold>

        //<editor-fold desc="Translate main">
        $mainTitle             = trans('email.title_export_excel');
        $mainPleasePressExport = trans('email.please_press_export_excel');
        $mainTokenTime         = trans('email.alert_token_time_export_excel');
        $mainButton            = trans('email.button_export_excel');
        $mainRegard            = trans('email.regard');
        $mainSignature         = trans('email.signature');
        $mainNote              = trans('email.note_download_excel');
        $main = array('title'             => $mainTitle,
                      'pleasePressExport' => $mainPleasePressExport,
                      'tokenTime'         => $mainTokenTime,
                      'button'            => $mainButton,
                      'regard'            => $mainRegard,
                      'note'              => $mainNote,
                      'signature'         => $mainSignature);
        $main['title']                 = $main['title'] ? $main['title'] : '';
        $main['pleasePressExport']     = $main['pleasePressExport'] ? $main['pleasePressExport'] : '';
        $main['tokenTime']             = $main['tokenTime'] ? $main['tokenTime'] : '';
        $main['button']                = $main['button'] ? $main['button'] : '';
        $main['regard']                = $main['regard'] ? $main['regard'] : '';
        $main['signature']             = $main['signature'] ? $main['signature'] : '';
        $main['note']                  = $main['note'] ? $main['note'] : '';
        //</editor-fold>

        //<editor-fold desc="Translate footer">
        if (empty(config('app.name'))) {
            throw new \Exception('Please config name app');
        }

        $nameHito = config('app.name');

        $currentYear = Carbon::now()->year;

        $copyRight = trans('email.copy_right');
        $copyRight = $copyRight ? $copyRight : '';
        $footer   = $currentYear.' '.$nameHito.$copyRight;
        //</editor-fold>

        //<editor-fold desc="Check email and name">
        if (!Auth::check()) {
            throw new \Exception('Don\'t have login');
        }
        $email    = Auth::user()->email;
        $name     = Auth::user()->name;
        if (!isset($email) || empty($email)) {
            throw new \Exception('Don\'t have email');
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new \Exception('Email invalid');
        }

        if (!isset($name) || empty($name)) {
            throw new \Exception('Don\'t have name');
        }
        //</editor-fold>

        //<editor-fold desc="Path file to download">
        $pathFile = $this->pathFile;
        if (!isset($pathFile) || empty($pathFile)) {
            throw new \Exception('Don\'t have path file');
        }
        //</editor-fold>

        //<editor-fold desc="View template">
        $view     = $this->view;
        if (empty($view)) {
            throw new \Exception('Don\'t have view');
        }
        //</editor-fold>

        //<editor-fold desc="Path to image logo">
        $pathToImage  = url(PATH_TO_FILE_IMAGE_LOGO);
        //</editor-fold>

        //<editor-fold desc="Send email">
         Mail::send($view, ['pathFile' => $pathFile,'main' => $main,'footer' => $footer,'pathToImage' => $pathToImage], function ($message) use ($subject, $name, $email) {
            $message->to($email, $name)
                    ->subject($subject);
        });
        //</editor-fold>

    }

    public function sendMail($employeeId = null) {
        //<editor-fold desc="Check subject">
        $subject = $this->subject;
        if (!isset($subject) || empty($subject)) {
            throw new \Exception('Don\'t have subject');
        }
        //</editor-fold>

        //<editor-fold desc="Translate main">
        $mainTitle             = !empty($this->title) ? $this->title : trans('email.title_excel');
        $mainTokenTime         = trans('email.alert_token_time_excel');
        $mainRegard            = trans('email.regard');
        $mainSignature         = trans('email.signature');
        $mainNote              = !empty($this->content) ? $this->content : trans('email.note_excel');
        $main = array('title'             => $mainTitle,
            'tokenTime'         => $mainTokenTime,
            'regard'            => $mainRegard,
            'note'              => $mainNote,
            'signature'         => $mainSignature);
        //</editor-fold>

        //<editor-fold desc="Translate footer">
        if (empty(config('app.name'))) {
            throw new \Exception('Please config name app');
        }

        $nameHito = config('app.name');

        $currentYear = Carbon::now()->year;

        $copyRight = trans('email.copy_right');
        $copyRight = $copyRight ? $copyRight : '';
        $footer   = $currentYear.' '.$nameHito.$copyRight;
        //</editor-fold>

        //<editor-fold desc="Check email and name">
        if (!Auth::check()) {
            throw new \Exception('Don\'t have login');
        }
        $email    = !empty($this->email) ? $this->email : Auth::user()->email;
        $name     = !empty($this->name) ? $this->name : Auth::user()->name;
        if (!isset($email) || empty($email)) {
            throw new \Exception('Don\'t have email');
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new \Exception('Email invalid');
        }

        if (!isset($name) || empty($name)) {
            throw new \Exception('Don\'t have name');
        }
        //</editor-fold>

        //<editor-fold desc="View template">
        $view     = $this->view;
        if (empty($view)) {
            throw new \Exception('Don\'t have view');
        }
        //</editor-fold>

        //<editor-fold desc="Path to image logo">
        $pathToImage  = url(PATH_TO_FILE_IMAGE_LOGO);
        //</editor-fold>


        //<editor-fold desc="Send email">
        try {
            Mail::send($view, ['main' => $main, 'footer' => $footer, 'pathToImage' => $pathToImage],
                function ($message) use ($subject, $name, $email) {
                    $message->to($email, $name)
                        ->subject($subject);
                });
            $params = [
                'employee_id' => ($employeeId) ? $employeeId : Auth::user()->employee_id,
                'title' => $this->title,
                'content' => $this->content,
                'status' => 1
            ];
            $emailNotificationReponese = resolve(EmailNotificationRepository::class);
            $emailNotificationReponese->create($params);
        } catch (\Exception $ex) {
            throw $ex;
        }
        //</editor-fold>
    }


    /**
     * sendChangePasswordEmail
     *
     * @return void
     * @throws \Exception
     * @author tri_quang
     * @since  2019-08-13
     */
    public function sendChangePasswordEmail(User $user, $reset = false)
    {
        //<editor-fold desc="Check subject">
        $subject = $this->subject;
        if (!isset($subject) || empty($subject)) {
            throw new \Exception('Don\'t have subject');
        }

        $main['title'] = trans('email.title');
        $main['notification'] = trans('email.change_password_successfully');
        $main['note'] = !empty($this->content) ? $this->content : trans('email.note_excel');
        //</editor-fold>

        //<editor-fold desc="Translate footer">
        if (empty(config('app.name'))) {
            throw new \Exception('Please config name app');
        }

        $nameHito = config('app.name');

        $currentYear = Carbon::now()->year;

        $copyRight = trans('email.copy_right');
        $copyRight = $copyRight ? $copyRight : '';
        $footer   = $currentYear.' '.$nameHito.$copyRight;
        //</editor-fold>

        //<editor-fold desc="Check email and name">
        if (!Auth::check() && $reset != true) {
            throw new \Exception('Don\'t have login');
        }
        $email    = $user->email;
        $name     = $user->name;
        if (!isset($email) || empty($email)) {
            throw new \Exception('Don\'t have email');
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new \Exception('Email invalid');
        }

        if (!isset($name) || empty($name)) {
            throw new \Exception('Don\'t have name');
        }
        //</editor-fold>

        //<editor-fold desc="View template">
        $view     = $this->view;
        if (empty($view)) {
            throw new \Exception('Don\'t have view');
        }
        //</editor-fold>

        //<editor-fold desc="Path to image logo">
        $pathToImage  = url(PATH_TO_FILE_IMAGE_LOGO);
        //</editor-fold>

        //<editor-fold desc="Send email">
         Mail::send($view, ['main' => $main, 'footer' => $footer,'pathToImage' => $pathToImage], function ($message) use ($subject, $name, $email) {
            $message->to($email, $name)
                    ->subject($subject);
        });
        $params = [
            'employee_id' => ($user->employee_id) ? $user->employee_id : Auth::user()->employee_id,
            'title' => $main['title'],
            'content' => $main['note'],
            'status' => 1
        ];
        $emailNotificationReponese = resolve(EmailNotificationRepository::class);
        $emailNotificationReponese->create($params);
        //</editor-fold>

    }

    public function sendMailWithQueue($employeeId = null) {
        //<editor-fold desc="Check subject">
        $subject = $this->subject;
        if (!isset($subject) || empty($subject)) {
            throw new \Exception('Don\'t have subject');
        }
        //</editor-fold>

        //<editor-fold desc="Translate main">
        $mainTitle             = !empty($this->title) ? $this->title : trans('email.title_excel');
        $mainTokenTime         = trans('email.alert_token_time_excel');
        $mainRegard            = trans('email.regard');
        $mainSignature         = trans('email.signature');
        $mainNote              = !empty($this->content) ? $this->content : trans('email.note_excel');
        $main = array('title'             => $mainTitle,
            'tokenTime'         => $mainTokenTime,
            'regard'            => $mainRegard,
            'note'              => $mainNote,
            'signature'         => $mainSignature);
        //</editor-fold>

        //<editor-fold desc="Translate footer">
        if (empty(config('app.name'))) {
            throw new \Exception('Please config name app');
        }

        $nameHito = config('app.name');

        $currentYear = Carbon::now()->year;

        $copyRight = trans('email.copy_right');
        $copyRight = $copyRight ? $copyRight : '';
        $footer   = $currentYear.' '.$nameHito.$copyRight;
        //</editor-fold>

        //<editor-fold desc="Check email and name">
        $email    = $this->email ?? '';
        $name     = $this->name ?? '';
        if (!isset($email) || empty($email)) {
            throw new \Exception('Don\'t have email');
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new \Exception('Email invalid');
        }

        if (!isset($name) || empty($name)) {
            throw new \Exception('Don\'t have name');
        }
        //</editor-fold>

        //<editor-fold desc="View template">
        $view     = $this->view;
        if (empty($view)) {
            throw new \Exception('Don\'t have view');
        }
        //</editor-fold>

        //<editor-fold desc="Path to image logo">
        $pathToImage  = url(PATH_TO_FILE_IMAGE_LOGO);
        //</editor-fold>

        //<editor-fold desc="Send email">
        try {
            Mail::send($view, ['main' => $main, 'footer' => $footer, 'pathToImage' => $pathToImage],
                function ($message) use ($subject, $name, $email) {
                    $message->to($email, $name)
                        ->subject($subject);
                });
            $params = [
                'employee_id' => $employeeId ?? 0,
                'title' => $this->title,
                'content' => $this->content,
                'status' => 1
            ];
            $emailNotificationReponese = resolve(EmailNotificationRepository::class);
            $emailNotificationReponese->create($params);
        } catch (\Exception $ex) {
            throw $ex;
        }
        //</editor-fold>
    }
}
