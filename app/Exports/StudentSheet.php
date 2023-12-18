<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;

class StudentSheet implements FromView, WithTitle, ShouldAutoSize, WithEvents
{
    private $group;

    public function __construct(array $group)
    {
        $this->group = $group;
    }

    public function view(): View
    {
        return view('export.profile.student', ['group' => $this->group]);
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return $this->group['title'];
    }

    /**
     * @return array
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function(AfterSheet $event) {
                $event->sheet->getDelegate()->getStyle('B')->getAlignment()->setHorizontal('left');
            },
        ];
    }
}
