<?php

namespace App\Imports;

use App\Article;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ArticlesImport implements ToCollection,WithHeadingRow
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        foreach ($collection as $row) {
            $data = Article::create([
                'name' => $row['matchcode'],
                'species_id' => $row['specie'],
                'quality_id' => $row['calitate'],
                'product_type_id' => $row['tip_produs'],
                'thickness' => $row['grosime'],
                'width' => $row['latime'],
            ]);
        }
    }
}
