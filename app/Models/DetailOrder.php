<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetailOrder extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'order_id',
        'product_id',
        'qty',
        'message',
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
