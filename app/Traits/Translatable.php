<?php

namespace App\Traits;

trait Translatable
{
    /**
     * You have to define your own FK and translations that you would like in your history display.
     * This variable has to be defined in each class using Translatable trait
     * The format is the following
     *      protected $dictionary = [
     *          'foreign_key' => [
     *                  'new_name' => 'translated_column_name',
     *                  'model' => 'model_name',
     *                  'property' => 'column_name'
     *          ]
     *      ];
     * /

    /**
     * Translate the Foreign Keys ID's from the auditable table into human readable data
     *
     * @param string $item
     * @return array
     */
    protected function translate(string $item)
    {
        foreach ($this->dictionary as $key => $val) {
            if ($item == $key) {
                return $val;
            }
        }

        return false;
    }
}
