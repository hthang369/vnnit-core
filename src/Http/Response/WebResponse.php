<?php
namespace Vnnit\Core\Http\Response;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Response;

/**
 * Class WebResponse
 * @package Vnnit\Core\Http\Response
 */
class WebResponse
{
    public static function notModified()
    {
        return response(null, Response::HTTP_NOT_MODIFIED);
    }

    public static function success($viewName, $data, $message = null)
    {
        if ($message === null) {
            $message = translate('response.success');
        }
        return static::makeResponse($viewName, true, Response::HTTP_OK, $message, $data);
    }

    public static function error($routeName, $errors, $message = null, int $code = Response::HTTP_FOUND)
    {
        if ($message === null) {
            $message = translate('response.error');
        }
        return static::makeRedirect($routeName, false, $code, $message, null, $errors);
    }

    public static function exception($routeName, $errors = [], $message = null, int $code = Response::HTTP_FOUND, array $headers = [])
    {
        if ($message === null) {
            $message = translate('response.exception');
        }
        return static::makeRedirect($routeName, false, $code, $message, null, $errors, $headers);
    }

    public static function created($viewName, $data, $message = null)
    {
        if ($message === null) {
            $message = translate('response.created');
        }
        return static::makeRedirect($viewName, true, Response::HTTP_FOUND, $message, $data);
    }

    public static function updated($viewName, $data, $message = null)
    {
        if ($message === null) {
            $message = translate('response.updated');
        }
        return static::makeRedirect($viewName, true, Response::HTTP_FOUND, $message, $data);
    }

    public static function deleted($viewName, $message = null)
    {
        if ($message === null) {
            $message = translate('response.deleted');
        }
        return static::makeRedirect($viewName, true, Response::HTTP_FOUND, $message);
    }

    public static function downloaded($viewName, $message = null)
    {
        if ($message === null) {
            $message = translate('response.downloaded');
        }
        return static::makeRedirect($viewName, true, Response::HTTP_FOUND, $message);
    }

    public static function uploaded($viewName, $message = null)
    {
        if ($message === null) {
            $message = translate('response.uploaded');
        }
        return static::makeRedirect($viewName, true, Response::HTTP_FOUND, $message);
    }

    public static function validateFail($routeName, $errors, $message = null)
    {
        if ($message === null) {
            $message = translate('response.validation_fail');
        }
        $errors = $errors instanceof Arrayable ? $errors->toArray() : $errors;
        return static::makeResponseError($routeName, Response::HTTP_FOUND, $errors, $message);
    }

    protected static function makeResponse(string $viewName, bool $success, int $code, string $message, $data = null, array $errors = [], array $headers = [])
    {
        $content = [
            'success' => $success,
            'message' => $message,
            'data'    => $data instanceof Arrayable ? $data->toArray() : $data,
            'errors'  => $errors
        ];

        return response()->view($viewName, $content, $code, $headers);
    }

    protected static function makeRedirect(string $routeName, bool $success, int $code, string $message, $data = null, $errors = [], array $headers = [])
    {
        $content = [
            'success' => $success,
            'message' => $message,
            'data'    => $data instanceof Arrayable ? $data->toArray() : $data,
            'errors'  => $errors
        ];

        return redirect()->intended($routeName, $code, $headers)->with($content)->withInput();
    }

    protected static function makeResponseError(string $routeName, int $code, array $errors = [], string $message = '', array $headers = [])
    {
        $content = [
            'success' => false,
            'message' => $message
        ];
        return redirect()->intended($routeName, $code, $headers)->with($content)->withInput()->withErrors($errors);
    }
}
