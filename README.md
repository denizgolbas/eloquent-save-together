![logo.png](art/logo.png)

# Eloquent SaveTogether

[![Latest Version on Packagist](https://img.shields.io/packagist/v/denizgolbas/eloquent-save-together.svg?style=flat-square)](https://packagist.org/packages/denizgolbas/eloquent-save-together)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/denizgolbas/eloquent-save-together/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/denizgolbas/eloquent-save-together/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/denizgolbas/eloquent-save-together/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/denizgolbas/eloquent-save-together/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/denizgolbas/eloquent-save-together.svg?style=flat-square)](https://packagist.org/packages/denizgolbas/eloquent-save-together)

A powerful Laravel package that allows you to save Eloquent models along with all their relationships in a single operation. Perfect for handling complex nested data structures from API requests or forms.

## Features

- 🚀 Save parent and all related models with one method call
- 🔄 Automatic handling of create/update operations based on ID presence
- 🗑️ Smart deletion of removed related records
- 🌳 Support for deeply nested relationships
- ⚡ Clean and intuitive API
- 🎯 Type-safe with full IDE support

## Installation

You can install the package via composer:

```bash
composer require denizgolbas/eloquent-save-together
```

Optionally, you can publish the config file to define custom relation mappings:

```bash
php artisan vendor:publish --tag="eloquent-save-together-config"
```

## Basic Usage

### 1. Add the trait to your model

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Denizgolbas\EloquentSaveTogether\Eloquent\EloquentSaveTogether;

class Product extends Model
{
    use EloquentSaveTogether;

    protected $fillable = [
        'name',
        'description',
        'sku',
        'base_price'
    ];

    /**
     * Define which relationships should be saved together
     *
     * Format:
     * - 'relationName' => false  : Save/update only, don't delete missing records
     * - 'relationName' => true   : Save/update AND delete records not in request
     */
    protected array $together = [
        'prices' => true,           // Delete prices not in request
        'additionalTaxes' => true,  // Delete taxes not in request
        'units' => false,           // Only save/update, don't delete
        'categories' => false,      // Only save/update, don't delete
    ];

    // Define your relationships
    public function prices()
    {
        return $this->hasMany(ProductPrice::class);
    }

    public function additionalTaxes()
    {
        return $this->belongsToMany(Tax::class, 'product_taxes');
    }

    public function units()
    {
        return $this->hasMany(ProductUnit::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }
}
```

### 2. Handle the request data

```php
<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'description' => 'nullable|string',
            'sku' => 'required|string|unique:products',
            'base_price' => 'required|numeric',

            // Related model data
            'prices' => 'array',
            'prices.*.id' => 'nullable|exists:product_prices,id',
            'prices.*.currency' => 'required|string',
            'prices.*.amount' => 'required|numeric',

            'additional_taxes' => 'array',
            'additional_taxes.*.id' => 'nullable|exists:taxes,id',
            'additional_taxes.*.name' => 'required_without:additional_taxes.*.id',
            'additional_taxes.*.rate' => 'required_without:additional_taxes.*.id',

            'units' => 'array',
            'units.*.id' => 'nullable|exists:product_units,id',
            'units.*.name' => 'required|string',
            'units.*.conversion_rate' => 'required|numeric',
        ]);

        $product = new Product();
        $product->fillTogether($data)
                ->saveTogether();

        return response()->json($product->load(['prices', 'additionalTaxes', 'units']));
    }

    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            // ... same validation rules
        ]);

        $product->fillTogether($data)
                ->saveTogether();

        return response()->json($product->load(['prices', 'additionalTaxes', 'units']));
    }
}
```

## Example Request Data

```json
{
    "name": "Premium Widget",
    "description": "A high-quality widget for all your needs",
    "sku": "WDG-001",
    "base_price": 99.99,

    "prices": [
        {
            "currency": "USD",
            "amount": 99.99
        },
        {
            "id": 15,
            "currency": "EUR",
            "amount": 89.99
        }
    ],

    "additional_taxes": [
        {
            "id": 1
        },
        {
            "name": "Luxury Tax",
            "rate": 0.15
        }
    ],

    "units": [
        {
            "name": "Box (12 pieces)",
            "conversion_rate": 12
        },
        {
            "id": 45,
            "name": "Case (144 pieces)",
            "conversion_rate": 144
        }
    ]
}
```

## Understanding the `$together` Property

The `$together` array property defines which relationships should be handled:

### Boolean Values (Delete Control)

- **`true`**: Full sync mode - Creates, updates AND deletes records not present in request
- **`false`**: Partial sync mode - Only creates and updates, preserves existing records not in request

```php
protected array $together = [
    'prices' => true,        // Will DELETE prices not in the request
    'categories' => false,   // Will KEEP existing categories not in the request
];
```

### Example Scenarios

#### Scenario 1: Full Sync (true)
```php
// Database has: Price IDs [1, 2, 3]
// Request has: Price IDs [2, 4] and one new price

'prices' => true  // After save: Database will have [2, 4, 5]
                  // ID 1 and 3 are DELETED
```

#### Scenario 2: Partial Sync (false)
```php
// Database has: Category IDs [1, 2, 3]
// Request has: Category IDs [2, 4]

'categories' => false  // After save: Database will have [1, 2, 3, 4]
                      // ID 1 and 3 are KEPT
```

## Advanced Usage

### Nested Relationships

The package supports deeply nested relationships. If a related model also uses the `EloquentSaveTogether` trait, its relationships will be saved recursively:

```php
// Order model
class Order extends Model
{
    use EloquentSaveTogether;

    protected array $together = [
        'items' => true,
        'customer' => false,
    ];
}

// OrderItem model
class OrderItem extends Model
{
    use EloquentSaveTogether;

    protected array $together = [
        'discounts' => true,
        'taxes' => false,
    ];
}

// Usage - saves Order -> OrderItems -> Discounts/Taxes
$order = new Order();
$order->fillTogether($data)->saveTogether();
```

### Custom Relation Mappings

If you're using custom relation classes, you can map them in the config:

```php
// config/eloquent-save-together.php
return [
    'relation_mappings' => [
        'App\Relations\CustomHasMany' => \Illuminate\Database\Eloquent\Relations\HasMany::class,
        'App\Relations\SpecialBelongsTo' => \Illuminate\Database\Eloquent\Relations\BelongsTo::class,
    ],
];
```

### Get Fillable Fields with Relations

To get all fillable fields including nested relations:

```php
$product = new Product();
$fillableStructure = $product->getRelatedWithSubRelations();

// Returns:
// [
//     'name',
//     'description',
//     'sku',
//     'base_price',
//     'prices' => ['currency', 'amount'],
//     'units' => ['name', 'conversion_rate']
// ]
```

## Supported Relationship Types

### One-to-One Relations
- `hasOne()`
- `belongsTo()`
- `morphTo()`
- `morphOne()`

### One-to-Many Relations
- `hasMany()`
- `morphMany()`

### Many-to-Many Relations
- `belongsToMany()`
- `morphToMany()`

## Important Notes

1. **Snake Case Convention**: Relationship names in request data should use snake_case
   ```php
   // Model relationship: additionalTaxes()
   // Request key: additional_taxes
   ```

2. **ID Field**: Include `id` field in request to update existing records
   ```json
   {
       "prices": [
           {"id": 1, "amount": 100},
           {"amount": 200}
       ]
   }
   ```
   - First item with `id: 1` updates existing record
   - Second item without `id` creates new record

3. **Validation**: Always validate your request data before using `fillTogether()`

4. **Mass Assignment**: Ensure related models have proper `$fillable` properties defined

## Credits

- [denizgolbas](https://github.com/denizgolbas)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
