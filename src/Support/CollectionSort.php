<?php

namespace Vnnit\Core\Support;

use Illuminate\Support\Collection;

class CollectionSort
{
    protected $orderBy;
    protected $direction;

    public function fromRequest()
    {
        $this->orderBy = request()->input('orderBy');
        $this->direction = request()->input('ascending', 1) ? 'asc' : 'desc';
    }

    public function handle(Collection $data)
    {
        if (!$this->orderBy) {
            return $data;
        }

        if ($this->direction == 'desc') {
            $data = $data->sortByDesc($this->orderBy);
        } else {
            $data = $data->sortBy($this->orderBy);
        }

        return $data->values();
    }
}
