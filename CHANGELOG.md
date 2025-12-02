# Changelog

All notable changes to `eloquent-save-together` will be documented in this file.

## [1.0.0] - 2024-12-XX

### Added
- Initial release of Eloquent SaveTogether package
- `EloquentSaveTogether` trait for saving parent and related models together
- Support for `hasOne`, `hasMany`, `belongsTo`, `belongsToMany`, `morphTo`, `morphOne`, `morphMany`, and `morphToMany` relationships
- `fillTogether()` method to fill parent and related models from request data
- `saveTogether()` method to save parent and all related models in a single operation
- Smart create/update detection based on ID presence in request data
- Configurable deletion of removed related records via boolean flags in `$together` property
- Support for deeply nested relationships (recursive saving)
- `getRelatedWithSubRelations()` method to get fillable fields including nested relations
- Custom relation mappings support via config file
- Configuration publishing command: `php artisan vendor:publish --tag="eloquent-save-together-config"`
- Pest test suite setup with Orchestra Testbench
- Full documentation and usage examples

### Requirements
- PHP ^8.2|^8.3
- Laravel ^10.0|^11.0
- Illuminate Database ^10.0|^11.0
- Illuminate Support ^10.0|^11.0
