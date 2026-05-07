<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ProductExport implements FromArray,WithHeadings,WithTitle,ShouldAutoSize
{
    use Exportable;

    protected $products;

    public function headings(): array
    {
        return [
            'Name',
            'Description',
            'URL',
            'Meta Title',
            'Meta Keywords',
            'Meta Description'
        ];
    }

    public function title(): string
    {
        return 'All Products';
    }

    public function __construct(array $products)
    {
        $this->products = $products;
    }

    public function array(): array
    {
        // insert/move your logic for modifying the array here

        return $this->products;
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    // public function collection()
    // {
    //     return Products::all();
    // }
}
