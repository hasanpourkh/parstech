<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithTitle;

class SingleSheetExport implements FromCollection, WithTitle
{
    protected $records;
    protected $title;

    public function __construct($records, $title)
    {
        $this->records = $records;
        $this->title = $title;
    }

    public function collection()
    {
        return collect($this->records);
    }

    public function title(): string
    {
        return $this->title;
    }
}
