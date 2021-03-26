<?php

namespace App\Helpers;

use Carbon\Carbon;
use Illuminate\Http\Request;

class ExcelHelper
{
    /**
     * Format export name.
     *
     * @param   string  $title
     * @param   string  $format
     *
     * @return  string
     */
    public static function formatExportName(string $title, string $format)
    {
        $carbon = Carbon::now()->format('_d_m_Y');

        return $title . $carbon . '.' . $format;
    }
}
