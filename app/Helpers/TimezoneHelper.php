<?php

namespace App\Helpers;

use Carbon\Carbon;

class TimezoneHelper
{
    public function convertToLocal(?Carbon $date, $timezoneOverride = null, string $format = null): string
    {
        if (is_null($date)) {
            return __('Empty');
        }

        if ($timezoneOverride) {
            $timezone = $timezoneOverride;
        } else {
            $timezone = (auth()->user()->timezone) ?? config('app.timezone');
        }

        $date->setTimezone($timezone);

        $format = $format ?? config('timezone.format');

        return config('timezone.enableTranslation') ? $date->translatedFormat($format) : $date->format($format);
    }

    private function formatTimezone(Carbon $date): string
    {
        $timezone = $date->format('e');
        $parts = explode('/', $timezone);

        if (count($parts) > 1) {
            return str_replace('_', ' ', $parts[1]).', '.$parts[0];
        }

        return str_replace('_', ' ', $parts[0]);
    }
}
