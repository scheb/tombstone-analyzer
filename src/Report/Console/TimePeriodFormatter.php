<?php

namespace Scheb\Tombstone\Analyzer\Report\Console;

class TimePeriodFormatter
{
    private const SECONDS_PER_DAY = 86400;

    public static function formatAge(string $date): string
    {
        $tombstoneDate = strtotime($date);
        if (!$tombstoneDate) {
            return '';
        }

        $daysPassed = floor((time() - $tombstoneDate) / self::SECONDS_PER_DAY);
        if ($daysPassed <= 0) {
            return '';
        }

        $weeksPassed = floor($daysPassed / 7);
        $daysPassed = $daysPassed % 7;

        $timePassed = $daysPassed.' days';
        if ($weeksPassed) {
            $timePassed = $weeksPassed.' weeks and '.$timePassed;
        }

        return $timePassed;
    }
}
