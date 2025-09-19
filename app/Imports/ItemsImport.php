<?php

namespace App\Imports;

use App\Models\Item;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Validation\Rule;
class ItemsImport implements ToModel, WithValidation,WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Item([
            'code' => $row['code'],
            'name' => $row['name'],
            'posisi' => $row['posisi'],
            'category' => $row['category'],
            'unit' => $row['unit']
        ]);
    }

    public function rules(): array
    {
        return [
            'code' => ['required','unique:items' ],
            'name' => [ 'required'],
            'posisi' => [ 'required'],
            'category' => [ 'required'],
            'unit' => [ 'required'],
           
        ];
    }
}
