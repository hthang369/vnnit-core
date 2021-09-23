<?php
namespace Vnnit\Core\Responses;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Response;

/**
 * Class WebResponse
 * @package App\Core\Http\Response
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
            $message = trans('response.success');
        }
        return static::makeResponse($viewName, true, Response::HTTP_OK, $message, $data);
    }

    public static function error($routeName, $message, int $code = Response::HTTP_FOUND)
    {
        if ($message === null) {
            $message = trans('response.error');
        }
        return static::makeRedirect($routeName, false, $code, $message);
    }

    public static function exception($routeName, $message, $errors = [], int $code = Response::HTTP_FOUND, array $headers = [])
    {
        if ($message === null) {
            $message = trans('response.exception');
        }
        return static::makeRedirect($routeName, false, $code, $message, null, $errors, $headers);
    }

    public static function created($viewName, $data, $message = null)
    {
        if ($message === null) {
            $message = trans('response.created');
        }
        return static::makeRedirect($viewName, true, Response::HTTP_FOUND, $message, $data);
    }

    public static function updated($viewName, $data, $message = null)
    {
        if ($message === null) {
            $message = trans('response.updated');
        }
        return static::makeRedirect($viewName, true, Response::HTTP_FOUND, $message, $data);
    }

    public static function deleted($viewName, $message = null)
    {
        if ($message === null) {
            $message = trans('response.deleted');
        }
        return static::makeRedirect($viewName, true, Response::HTTP_FOUND, $message);
    }

    public static function validateFail($routeName, array $errors, $message = null)
    {
        if ($message === null) {
            $message = trans('response.validation_fail');
        }
        return static::makeResponseError($routeName, Response::HTTP_UNPROCESSABLE_ENTITY, $message, $errors);
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

        return redirect()->intended($routeName, $code, $headers)->with($content);
    }

    protected static function makeResponseError(string $routeName, int $code, array $errors = [], array $headers = [])
    {
        return redirect()->intended($routeName, $code, $headers)->withInput()->withErrors($errors);
    }
}
