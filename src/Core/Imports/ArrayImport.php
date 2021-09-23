<?php

namespace Vnnit\Core\Imports;

use Maatwebsite\Excel\Concerns\ToArray;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ArrayImport implements ToArray, WithHeadingRow
{
    protected $headingRow = 1;
    protected $data;

    /**
     * @param array $array
     */
    public function array(array $array)
    {
        $this->data = $array;
    }

    public function setHeadingRow($row)
    {
        $this->headingRow = $row;
        return $this;
    }

    public function data()
    {
        return $this->data;
    }
}
