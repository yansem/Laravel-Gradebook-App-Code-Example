<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;

class TeacherSheet implements FromView, WithTitle, ShouldAutoSize, WithEvents
{
    private $subject;

    public function __construct(array $subject)
    {
        $this->subject = $subject;
    }

    public function view(): View
    {
        return view('export.profile.teacher', ['subject' => $this->subject]);
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return $this->subject['title'];
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
