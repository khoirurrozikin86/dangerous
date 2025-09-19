<?php

namespace App\Exports;

use App\Models\Peminjaman;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Collection;

class PeminjamanExport implements FromCollection, WithHeadings
{
  
    protected $data;

    public function __construct(Collection $data)
    {
        $this->data = $data;
    }

    public function collection()
    {
        // return $this->data;

        $formattedData = $this->data->map(function ($peminjaman) {
            return [
                $peminjaman->id, // No
                $peminjaman->no_trx_out, // TRX OUT
                $peminjaman->created_at->format('Y-m-d H:i:s'), // DATE IN
                $peminjaman->employee->nik, // NIK
                $peminjaman->employee->name, // NAME
                $peminjaman->employee->department, // DEPT.
                $peminjaman->no_trx_return, // TRX RETURN
                $peminjaman->item->code, // SKU
                $peminjaman->item->name, // NAME
                $peminjaman->updated_at->format('Y-m-d H:i:s'), // DATE OUT
                $peminjaman->remark // REMARK
            ];
        });

        return $formattedData;
    }

    public function headings(): array
    {
        // Sesuaikan dengan kolom-kolom yang ingin Anda tampilkan dalam file Excel
        return [
            'No',
            'TRX OUT',
            'DATE IN',
            'NIK',
            'NAME',
            'DEPT.',
            'TRX RETURN',
            'SKU',
            'NAME',
            'DATE OUT',
            'REMARK',
        ];
    }

}
