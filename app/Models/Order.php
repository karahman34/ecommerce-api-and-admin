<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
    ];

    /**
     * Get the buyer model.
     *
     * @return  BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the product model.
     *
     * @return  BelongsTo
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get detail orders list.
     *
     * @return  BelongsToMany
     */
    public function detail_orders()
    {
        return $this->belongsToMany(Product::class, 'detail_orders')
                    ->withTimestamps()
                    ->withPivot('qty', 'message');
    }

    /**
     * Get the related transaction model.
     *
     * @return  HasOne
     */
    public function transaction()
    {
        return $this->hasOne(Transaction::class);
    }
}
