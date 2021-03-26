<?php

namespace App\Helpers;

use Carbon\Carbon;
use Illuminate\Http\Request;

class ExcelHelper
{
    /**
     * Allowed export formats.
     *
     * @var array
     */
    public static $allowed_export_formats =  ['xlsx', 'csv'];
    
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
