<?php

namespace Vnnit\Core\Responses;

use Vnnit\Core\Traits\SendResponse;
use Symfony\Component\HttpFoundation\Response;

class BaseResponse
{
    use SendResponse;
    public function data($data)
    {
        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    public function created($data, $message = null)
    {
        $message = $message ?: trans('common.successfully');
        return response()->json([
            'success' => true,
            'message' => $message,
            'data'    => $data
        ], Response::HTTP_CREATED);
    }

    public function updated($data, $message = null)
    {
        $message = $message ?: trans('common.successfully');
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data
        ], Response::HTTP_OK);
    }

    public function deleted($message = null)
    {
        $message = $message ?: trans('common.successfully');
        return response()->json([
            'success' => true,
            'message' => $message,
        ], Response::HTTP_OK);
    }

    public function error($error, $message = null)
    {
        $message = $message ?: trans('common.request_failed');
        return response()->json([
            'success' => false,
            'message' => $message,
            'error'   => $error
        ], Response::HTTP_CONFLICT);
    }

    public function serverError($error, $message = null)
    {
        $message = $message ?: trans('common.request_failed');
        return response()->json([
            'success' => false,
            'message' => $message,
            'error'   => $error
        ], Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    public function validationError($error, $message = null)
    {
        $message = $message ?: trans('validation.validation_exception');
        return response()->json([
            'success'    => false,
            'message'    => $message,
            'validation' => $error
        ], Response::HTTP_FORBIDDEN);
    }
}
