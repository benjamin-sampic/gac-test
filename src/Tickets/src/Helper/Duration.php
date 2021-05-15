<?php

namespace Tickets\Helper;

class Duration
{
    /**
     * Format seconds to HH:mm:ss format
     * @param  int    $time Time in seconds
     * @return string
     */
    public static function formatSecondsToHHMMSS(int $time): string
    {
        $hours = floor($time / 3600);
        $minutes = floor(($time % 3600) / 60);
        $seconds = floor(($time % 60));

        return $hours.':'.$minutes.':'.$seconds;
    }
}
