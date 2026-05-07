<?php

namespace App\Exports;


use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ReferralExport implements FromArray,WithHeadings,WithTitle,ShouldAutoSize
{

    use Exportable;

    protected $referrals;

    public function headings(): array
    {
        return [
            'Referral URL',
            'Source Type',
            'Campaign',
            'IP Address'            
        ];
    }

    public function title(): string
    {
        return 'All Referral';
    }

    public function __construct(array $referrals)
    {
        $this->referrals = $referrals;
    }

    public function array(): array
    {
        // insert/move your logic for modifying the array here

        return $this->referrals;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    // public function collection()
    // {
    //     return Referral::all();
    // }
}
