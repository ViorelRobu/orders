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

            if ($row['finisaje'] != '') {
                $finisaje = explode(',', $row['finisaje']);
                $finisaje = array_map(function($item) {
                    return trim($item);
                }, $finisaje);
                $finisaje = implode(',', $finisaje);
            } else {
                $finisaje = null;
            }

            $article = new Article();
            $article->name = $row['matchcode'];
            $article->species_id = $row['specie'];
            $article->quality_id = $row['calitate'];
            $article->product_type_id = $row['tip_produs'];
            $article->default_refinements = $finisaje;
            $article->thickness = $row['grosime'];
            $article->width = $row['latime'];
            $article->save();
        }
    }
}
