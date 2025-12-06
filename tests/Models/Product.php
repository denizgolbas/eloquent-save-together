<?php

namespace Denizgolbas\EloquentSaveTogether\Tests\Models;

use Denizgolbas\EloquentSaveTogether\Eloquent\EloquentSaveTogether;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Product extends Model
{
    use EloquentSaveTogether;

    protected $fillable = [
        'name',
        'description',
        'sku',
        'price',
        'category_id',
    ];

    protected array $together = [
        'prices' => true,
        'category' => false,
        'tags' => false,
        'details' => false,
    ];

    public function prices(): HasMany
    {
        return $this->hasMany(ProductPrice::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'product_tags');
    }

    public function details(): HasOne
    {
        return $this->hasOne(ProductDetail::class);
    }
}
