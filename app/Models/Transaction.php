<?php

namespace App\Models;

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
}
