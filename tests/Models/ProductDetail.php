<?php

namespace Denizgolbas\EloquentSaveTogether\Tests\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductDetail extends Model
{
    protected $fillable = [
        'product_id',
        'weight',
        'dimensions',
        'warranty',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
