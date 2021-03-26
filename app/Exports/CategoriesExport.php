<?php

namespace App\Exports;

use App\Models\Category;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CategoriesExport implements FromCollection, ShouldAutoSize, WithHeadings
{
    private $take;

    public function __construct(int $take)
    {
        $this->take = $take;
    }

    public function headings(): array
    {
        return [
            'id',
            'name',
            'created_at',
            'updated_at',
        ];
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Category::select('id', 'name', 'created_at', 'updated_at')->limit($this->take)->get();
    }
}
