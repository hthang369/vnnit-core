<?php

namespace Vnnit\Core\Contracts;

use Illuminate\Http\Request;

/**
 * Interface BaseControllerInterface
 * @package Laka\Contracts
 */
interface BaseControllerInterface
{
    /**
     * @param $data
     * @param $rules
     * @return mixed
     */
    public function validator($data, $rules);

    /**
     * @param $id
     * @return mixed
     */
    public function view($id);

    /**
     * @return mixed
     */
    public function index();

    /**
     * @param Request $request
     * @return mixed
     */
    public function create();

    /**
     * @param Request $request
     * @return mixed
     */
    public function store(Request $request);

    /**
     * @param Request $request
     * @return mixed
     */
    public function edit(int $id);

    /**
     * @param int $id
     * @return mixed
     */
    public function show(int $id);

    /**
     * @param Request $request
     * @param int $id
     * @return mixed
     */
    public function update(Request $request, int $id);

    /**
     * @param int $id
     * @return mixed
     */
    public function destroy(int $id);

    /**
     * @param Request $request
     * @param int $id
     * @return mixed
     */
    public function import(Request $request, int $id);

    /**
     * @param Request $request
     * @param int $id
     * @return mixed
     */
    public function export(Request $request, int $id);
}
