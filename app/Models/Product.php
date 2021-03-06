<?php

namespace App\Models;

use App\Helpers\MoneyHelper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Product extends Model
{
    use HasFactory;

    /**
     * Upload folder name.
     *
     * @var string
     */
    public static $uploadFolder = 'products';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'category_id',
        'name',
        'stock',
        'price',
        'description',
    ];

    /**
     * Get product images.
     *
     * @return  HasMany
     */
    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    /**
     * Get the product's category.
     *
     * @return  BelongsTo
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the product's thumbnal.
     *
     * @return  HasOne
     */
    public function thumbnail()
    {
        return $this->hasOne(ProductImage::class);
    }

    /**
     * Get orders.
     *
     * @return  HasMany
     */
    public function detail_orders()
    {
        return $this->belongsToMany(DetailOrder::class, 'detail_orders')
                    ->withTimestamps()
                    ->withPivot('qty', 'message');
    }

    /**
     * Convert price to rupiah.
     *
     * @return  string
     */
    public function priceInRupiah()
    {
        return MoneyHelper::convertToRupiah($this->price);
    }
}
