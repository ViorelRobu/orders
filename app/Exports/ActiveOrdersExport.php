<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;

class ActiveOrdersExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        //
    }
}
