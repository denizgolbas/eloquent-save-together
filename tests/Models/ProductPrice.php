<?php

namespace Denizgolbas\EloquentSaveTogether\Tests\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductPrice extends Model
{
    protected $fillable = [
        'product_id',
        'currency',
        'amount',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
