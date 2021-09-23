<?php

namespace Vnnit\Core\Responses;

class StubPaginateResponse implements PaginateResponse
{
    public function paginate($data)
    {
        return $data;
    }
}
