<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class StudentExport implements WithMultipleSheets
{
    private $groups;

    public function __construct($groups){
        $this->groups = $groups;
    }

    public function sheets(): array
    {
        $sheets = [];

        foreach ($this->groups as $group) {
            $sheets[] = new StudentSheet($group);
        }

        return $sheets;
    }
}
