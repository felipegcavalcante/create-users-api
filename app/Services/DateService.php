<?php

namespace App\Services;

use Carbon\Carbon;

class DateService
{
    public function verifyAgeBetween(string $dateOfBirth, int $minAge, int $maxAge): bool
    {
        $today = Carbon::today();
        $idade = Carbon::createFromFormat('Y-m-d', $dateOfBirth);

        if ($today->floatDiffInYears($idade) < $minAge) {
            return false;
        }

        if ($today->floatDiffInYears($idade) > $maxAge) {
            return false;
        }

        return true;
    }
}
