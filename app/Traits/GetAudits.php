<?php

namespace App\Traits;

use App\User;
use App\Traits\Translatable;

trait GetAudits
{
    use Translatable;

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
}
