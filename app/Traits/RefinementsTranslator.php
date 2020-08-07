<?php

namespace App\Traits;

use App\Refinement;

trait RefinementsTranslator {

    /**
     * Convert the string of refinements ID's into a string of refinements names
     *
     * @param string $string
     * @return string
     */
    public function translateForHumans($string)
    {
        $refinements = [];

        $refinement_ids = explode(',', $string);
        foreach ($refinement_ids as $item) {
            $refinement = Refinement::find($item);
            $refinements[] = $refinement->name;
        }

        return implode(',',$refinements);
    }
}
