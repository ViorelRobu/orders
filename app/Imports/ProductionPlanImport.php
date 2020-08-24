<?php

namespace App\Imports;

use App\OrderDetail;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ProductionPlanImport implements ToCollection, WithHeadingRow
{
    /**
    * @param Collection $rows
    *
    * @return void
    */
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            $detail = OrderDetail::find($row['id']);
            $detail->batch = $row['lot'];
            $detail->produced_batch = $row['produs'];
            $detail->save();
        }
    }
}
