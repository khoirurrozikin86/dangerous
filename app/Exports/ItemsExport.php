<?php

namespace App\Exports;

use App\Models\Item;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ItemsExport implements FromCollection, WithHeadings, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Item::all();
    }

    public function headings(): array
    {
        return [
            'NO',
            'SKU',
            'NAME',
            'POSISI',
            'CATEGORY',
            'UNIT',
            'STATUS',
        ];
    }

    /**
     * @param mixed $row
     * @return array
     */
    public function map($row): array
    {
        static $index = 0;
        $index++;

        $status = ($row->status == 0) ? 'Ready' : 'Dipinjam';

        return [
            // Add sequential number instead of ID
            $index,
            $row->code,
            $row->name,
            $row->posisi,
            $row->category,  
            $row->unit,             
            $status,
        ];
    }
}
