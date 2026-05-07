<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class WebinarExport implements FromArray,WithHeadings,WithTitle,ShouldAutoSize
{
    use Exportable;

    protected $users;

    public function headings(): array
    {
        return [
            'Webinar',
            'Name',
            'Email',
            'Contact No',
            'Address',
            'Company',
            'Country',
            'Attended date'
        ];
    }

    public function title(): string
    {
        return 'Webinar Users';
    }

    public function __construct(array $users)
    {
        $this->users = $users;
    }

    public function array(): array
    {
        // insert/move your logic for modifying the array here

        return $this->users;
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    // public function collection()
    // {
    //     return Products::all();
    // }
}
