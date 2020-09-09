<?php

namespace App\Traits;

use App\User;
use App\Traits\Translatable;

trait GetAudits
{
    /*
    | You have to define your own FK and translations that you would like in your history display.
    | This variable has to be defined in each class using the GetAudits trait
    | The format is the following
    |      protected $dictionary = [
    |          'foreign_key' => [
    |                   'new_name' => 'translated_column_name',
    |                   'model' => 'model_name',
    |                  'property' => 'column_name'
    |          ]
    |      ];
    */


     /**
     * Get the audits for a given model
     *
     * @param string $model
     * @param integer $model_id
     * @return json
     */
    public function getAudits($model, $model_id)
    {
        $modelData = $model::find($model_id);

        foreach ($modelData->audits as $auditData) {
            $user = User::find($auditData->user_id);
            $auditData->user = $user->name;

            $old_values = [];
            foreach ($auditData->old_values as $key => $value) {
                $translated = $this->translate($key);
                if ($translated) {
                    $val = $translated['model']::find($value);
                    $old_values[$translated['new_name']] = $val->{$translated['property']};
                } else {
                    $old_values[$key] = $value;
                }
            }

            $new_values = [];
            foreach ($auditData->new_values as $key => $value) {
                $translated = $this->translate($key);
                if ($translated) {
                    $val = $translated['model']::find($value);
                    $new_values[$translated['new_name']] = $val->{$translated['property']};
                } else {
                    $new_values[$key] = $value;
                }
            }

            $auditData->old_values = $old_values;
            $auditData->new_values = $new_values;
        }

        return $modelData->audits;
    }

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
