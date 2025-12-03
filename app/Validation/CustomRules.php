<?php

namespace App\Validation;

class CustomRules
{
    public function not_past_date(array|string $value, string $fields, array $data): bool
    {
        if (is_array($value)) {
            foreach ($value as $date) {
                if (strtotime($date) < time()) {
                    return false;
                }
            }
            return true;
        }

        return strtotime($value) >= time();
    }
}
