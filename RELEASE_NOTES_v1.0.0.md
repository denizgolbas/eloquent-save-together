# 🎉 Eloquent SaveTogether v1.0.0 - Initial Release

## Overview

**Eloquent SaveTogether** is a powerful Laravel package that simplifies saving Eloquent models along with all their relationships in a single, elegant operation. Perfect for handling complex nested data structures from API requests or forms.

## ✨ Key Features

- 🚀 **One-Call Saving**: Save parent and all related models with a single method call
- 🔄 **Smart Operations**: Automatic create/update detection based on ID presence
- 🗑️ **Intelligent Deletion**: Configurable deletion of removed related records
- 🌳 **Nested Support**: Full support for deeply nested relationships
- ⚡ **Clean API**: Intuitive and type-safe with full IDE support
- 🎯 **Flexible Control**: Choose which relationships to sync and how

## 🎯 What's Included

### Core Functionality
- `EloquentSaveTogether` trait for your models
- `fillTogether()` method to populate parent and related models
- `saveTogether()` method to persist everything in one operation
- Support for all major Eloquent relationship types

### Relationship Support
- ✅ `hasOne()` / `hasMany()`
- ✅ `belongsTo()` / `belongsToMany()`
- ✅ `morphTo()` / `morphOne()` / `morphMany()` / `morphToMany()`

### Additional Features
- Configuration file for custom relation mappings
- Recursive saving for nested relationships
- `getRelatedWithSubRelations()` helper method
- Artisan command for publishing configuration

## 📦 Installation

```bash
composer require denizgolbas/eloquent-save-together
```

## 🚀 Quick Start

1. Add the trait to your model:
```php
use Denizgolbas\EloquentSaveTogether\Eloquent\EloquentSaveTogether;

class Product extends Model
{
    use EloquentSaveTogether;
    
    protected array $together = [
        'prices' => true,
        'categories' => false,
    ];
}
```

2. Use it in your controller:
```php
$product = new Product();
$product->fillTogether($request->all())
        ->saveTogether();
```

## 📚 Documentation

Full documentation is available in the [README.md](README.md) file, including:
- Detailed usage examples
- Relationship configuration guide
- Advanced nested relationship handling
- Best practices and important notes

## 🔧 Requirements

- PHP ^8.2|^8.3
- Laravel ^10.0|^11.0

## 🧪 Testing

The package includes a Pest test suite and is fully tested with Orchestra Testbench.

## 📝 License

MIT License - see [LICENSE.md](LICENSE.md) for details.

## 🙏 Credits

Developed by [denizgolbas](https://github.com/denizgolbas)

---

**Happy coding!** 🎉

