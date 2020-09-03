<?php

namespace App\Imports;

use App\OrderDetail;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class DeliveriesImport implements ToCollection, WithHeadingRow
{
    /**
    * @param Collection $rows
    */
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            $detail = OrderDetail::find($row['id']);
            if ($row['livrat'] != null) {
                $detail->produced_ticom = '1';
                $detail->loading_date = Date::excelToDateTimeObject($row['livrat']);
            } else {
                $detail->produced_ticom = $row['pal'];
                $detail->loading_date = null;
            }
            $detail->save();
        }
    }
}
