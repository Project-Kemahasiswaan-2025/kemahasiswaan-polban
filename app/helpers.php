<?php

if (!function_exists('format_date')) {
    /**
     * Format date using Carbon with localization support.
     *
     * @param string|null $date
     * @param string $format
     * @return string
     */
    function format_date($date, $format = 'd F Y')
    {
        if (!$date) {
            return '-';
        }

        try {
            return \Carbon\Carbon::parse($date)->translatedFormat($format);
        } catch (\Exception $e) {
            return $date;
        }
    }
}
