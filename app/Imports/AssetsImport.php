<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class AssetsImport implements ToCollection, WithHeadingRow
{
    protected $rows;

    public function collection(Collection $rows)
    {
        $this->rows = $rows;
    }

    public function getRows()
    {
        return $this->rows ?? collect();
    }
}
