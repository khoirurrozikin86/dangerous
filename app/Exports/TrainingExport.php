<?php

namespace App\Exports;

use App\Models\Training;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TrainingExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Training::with('employee', 'basicoperation')->get()->map(function ($training) {
            return [
                'Training No' => $training->training_no,
                'Date' => $training->created_at,
                'NIK' => $training->employee->nik,
                'Name' => $training->employee->name,
                'Position' => $training->employee->posisi,
                'OP Code' => $training->basicoperation->op_code,
                'OP Name' => $training->basicoperation->op_name,
                'Remark' => $training->remark,
            ];
        });

        
    }

    public function headings(): array
    {
        return [
            'Training No',
            'Date',
            'NIK',
            'Name',
            'Position',
            'OP Code',
            'OP Name',
            'Remark',
        ];
    }
}
