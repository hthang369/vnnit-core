<?php

namespace Vnnit\Core\Responses;

use Illuminate\Http\Request;
use Vnnit\Core\Http\Response\JsonResponse;
use Vnnit\Core\Http\Response\WebResponse;

class BaseResponse
{
    public function data(Request $request, $data, $viewName = '', $message = null)
    {
        return $this->makeResponse($request, 'success', $data, $viewName, [], $message);
    }

    public function created(Request $request, $data, $viewName = '', $message = null)
    {
        return $this->makeResponse($request, 'created', $data, $viewName, [], $message);
    }

    public function updated(Request $request, $data, $viewName = '', $message = null)
    {
        return $this->makeResponse($request, 'updated', $data, $viewName, [], $message);
    }

    public function deleted(Request $request, $viewName = '', $message = null)
    {
        return $this->makeResponse($request, 'deleted', null, $viewName, [], $message);
    }

    public function error(Request $request, $error, $viewName = '', $message = null)
    {
        return $this->makeResponse($request, 'exception', null, $viewName, $error, $message);
    }

    public function serverError(Request $request, $error, $viewName = '', $message = null)
    {
        return $this->makeResponse($request, 'error', null, $viewName, $error, $message);
    }

    public function validationError(Request $request, $error, $viewName = '', $message = null)
    {
        return $this->makeResponse($request, 'validateFail', null, $viewName, $error, $message);
    }

    private function makeResponse(Request $request, $method, $data, $viewName = '', $errors = [], $message = null)
    {
        if ($request->wantsJson()) {
            return $this->makeJsonResponse($method, $data, $errors, $message);
        }
        return $this->makeWebResponse($method, $viewName, $data, $errors, $message);
    }

    private function makeJsonResponse($method, $data, $errors = [], $message = null)
    {
        if (in_array($method, ['success', 'created', 'updated']))
            return JsonResponse::{$method}($data, $message);
        else if(in_array($method, ['error', 'exception', 'validateFail']))
            return JsonResponse::{$method}($errors, $message);
        else
            return JsonResponse::{$method}($message);
    }

    private function makeWebResponse($method, $viewName, $data, $errors = [], $message = null)
    {
        if (in_array($method, ['success', 'created', 'updated']))
            return WebResponse::{$method}($viewName, $data, $message);
        else if(in_array($method, ['error', 'exception', 'validateFail']))
            return WebResponse::{$method}($viewName, $errors, $message);
        else
            return WebResponse::{$method}($viewName, $message);
    }
}
