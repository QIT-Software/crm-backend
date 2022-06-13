<?php

namespace App\Traits;

trait ArrayOperations
{
    protected function getMergedArray() {
        $args = func_get_args();
        $total = $args[0];

        // if $total array is empty, which is passed first every time,
        // no sense continue the method
        if (! count($total)) {
            return [];
        }

        $result = [];

        for ($group = 0; $group < count($total); $group++) {
            $merged = (array) $total[$group];
            $id = $total[$group]->id;
            for ($period = 1; $period < count($args); $period++) {
                for ($subGroup = 0; $subGroup < count($args[$period]); $subGroup++) {
                    // if another sub period (for example: week, month, year etc.)
                    // has id like in total group then merge them into one
                    if ($id == $args[$period][$subGroup]->id) {
                        $merged = array_merge((array) $args[$period][$subGroup], $merged);
                    }
                }
            }
            $result[] = $merged;
        }
        return $result;
    }
}