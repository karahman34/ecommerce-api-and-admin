<?php

namespace App\Models;

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
     * Get buyers that have buy this product.
     *
     * @return  HasMany
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
