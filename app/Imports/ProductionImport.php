<?php

namespace App\Imports;

use App\OrderDetail;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ProductionImport implements ToCollection, WithHeadingRow
{
    /**
     * @param Collection $rows
     *
     * @return void
     */
    public function collection(Collection $rows)
    {
        // dd($rows);
        foreach ($rows as $row) {
            $detail = OrderDetail::find($row['id']);
            $detail->produced_ticom = $row['pal'];
            $detail->save();
        }
    }
}
