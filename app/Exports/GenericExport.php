<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class GenericExport implements WithMultipleSheets
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function sheets(): array
    {
        $sheets = [];
        foreach ($this->data as $table => $records) {
            $sheets[] = new \App\Exports\SingleSheetExport($records, $table);
        }
        return $sheets;
    }
}
