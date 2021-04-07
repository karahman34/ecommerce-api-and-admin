<?php

namespace App\Models;

use App\Helpers\MoneyHelper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'order_id',
        'total',
        'name',
        'address',
        'telephone',
    ];

    /**
     * Get the order model.
     *
     * @return  BelongsTo
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Convert total in rupiah.
     *
     * @return  string
     */
    public function totalInRupiah()
    {
        return MoneyHelper::convertToRupiah($this->total);
    }
}
