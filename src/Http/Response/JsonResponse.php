<?php

namespace Vnnit\Core\Http\Response;

use Illuminate\Contracts\Support\Arrayable;
use Symfony\Component\HttpFoundation\Response;

class JsonResponse
{
    public static function success($data, $message = null)
    {
        if ($message === null) {
            $message = translate('response.success');
        }
        return static::makeResponse(true, Response::HTTP_OK, $message, $data);
    }

    public static function notModified()
    {
        return response(null, Response::HTTP_NOT_MODIFIED);
    }

    public static function error($errors, $message = null, int $code = Response::HTTP_INTERNAL_SERVER_ERROR)
    {
        if ($message === null) {
            $message = translate('response.error');
        }
        return static::makeResponse(false, $code, $message, null, $errors);
    }

    public static function exception(array $errors = [], string $message = null, int $code = Response::HTTP_CONFLICT, array $headers = [])
    {
        if ($message === null) {
            $message = translate('response.exception');
        }
        return static::makeResponse(false, $code, $message, null, $errors, $headers);
    }

    public static function created($data, $message = null)
    {
        if ($message === null) {
            $message = translate('response.created');
        }
        return static::makeResponse(true, Response::HTTP_CREATED, $message, $data);
    }

    public static function updated($data, $message = null)
    {
        if ($message === null) {
            $message = translate('response.updated');
        }
        return static::makeResponse(true, Response::HTTP_OK, $message, $data);
    }

    public static function deleted($message = null)
    {
        if ($message === null) {
            $message = translate('response.deleted');
        }
        return static::makeResponse(true, Response::HTTP_OK, $message);
    }

    public static function validateFail(array $errors, $message = null)
    {
        if ($message === null) {
            $message = translate('response.validation_fail');
        }
        return static::makeResponse(false, Response::HTTP_UNPROCESSABLE_ENTITY, $message, null, $errors);
    }

    protected static function makeResponse(bool $success, int $code, string $message, $data = null, array $errors = [], array $headers = [])
    {
        $content = [
            'success' => $success,
            'message' => $message,
            'data'    => $data instanceof Arrayable ? $data->toArray() : $data,
            'errors'  => $errors
        ];

        return response($content, $code, $headers);
    }
}
