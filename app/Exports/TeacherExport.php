<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class TeacherExport implements WithMultipleSheets
{
    private $subjects;

    public function __construct($subjects){
        $this->subjects = $subjects;
    }

    public function sheets(): array
    {
        $sheets = [];

        foreach ($this->subjects as $param) {
            $sheets[] = new TeacherSheet($param);
        }

        return $sheets;
    }
}
