<?php

namespace App\Helpers;

class MoneyHelper
{
    /**
     * Convert price to rupiah.
     *
     * @param   int  $price
     *
     * @return  string
     */
    public static function convertToRupiah(int $price)
    {
        $prefix = 'Rp ';

        return $prefix . number_format($price, 2, ',', '.');
    }
}
