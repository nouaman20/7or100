<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Product extends Model
{
    use HasUuids;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'is_active',
        'is_pack',
        'is_featured',
        'category_id',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'is_pack' => 'boolean',
            'is_featured' => 'boolean',
        ];
    }

    public function product_variants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function product_images()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function order_items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function packItems(): BelongsToMany
    {
        return $this->belongsToMany(self::class, 'product_pack_items', 'pack_product_id', 'product_id')->withTimestamps();
    }

    public function includedInPacks(): BelongsToMany
    {
        return $this->belongsToMany(self::class, 'product_pack_items', 'product_id', 'pack_product_id')->withTimestamps();
    }
}
