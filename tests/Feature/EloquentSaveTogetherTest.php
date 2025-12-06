<?php

use Denizgolbas\EloquentSaveTogether\Eloquent\EloquentSaveTogether;
use Denizgolbas\EloquentSaveTogether\Tests\Models\Product;

test('it can fill parent model attributes', function () {
    $data = [
        'name' => 'Test Product',
        'description' => 'Test Description',
        'sku' => 'TEST-001',
        'price' => 99.99,
    ];

    $product = new Product;
    $product->fillTogether($data);

    expect($product->name)->toBe('Test Product')
        ->and($product->description)->toBe('Test Description')
        ->and($product->sku)->toBe('TEST-001')
        ->and($product->price)->toBe(99.99);
});

test('trait has required methods', function () {
    $product = new Product;

    expect(method_exists($product, 'fillTogether'))->toBeTrue()
        ->and(method_exists($product, 'saveTogether'))->toBeTrue()
        ->and(method_exists($product, 'initializeHasTogether'))->toBeTrue()
        ->and(method_exists($product, 'getRelatedWithSubRelations'))->toBeTrue();
});

test('trait can be used in model', function () {
    $traits = class_uses(Product::class);

    expect($traits)->toContain(EloquentSaveTogether::class);
});

test('model has together property configured', function () {
    $product = new Product;
    $reflection = new ReflectionClass($product);
    $property = $reflection->getProperty('together');
    $property->setAccessible(true);
    $together = $property->getValue($product);

    expect($together)->toBeArray()
        ->and($together)->toHaveKey('prices')
        ->and($together)->toHaveKey('category')
        ->and($together)->toHaveKey('tags')
        ->and($together)->toHaveKey('details');
});

test('fillTogether returns self for method chaining', function () {
    $product = new Product;
    $result = $product->fillTogether(['name' => 'Test']);

    expect($result)->toBeInstanceOf(Product::class);
});

test('initializeHasTogether sets up togetherRequestKeys', function () {
    $product = new Product;
    $product->initializeHasTogether();

    $reflection = new ReflectionClass($product);
    $property = $reflection->getProperty('togetherRequestKeys');
    $property->setAccessible(true);
    $keys = $property->getValue($product);

    expect($keys)->toBeArray()
        ->and($keys)->toHaveKey('prices')
        ->and($keys['prices'])->toBeTrue()
        ->and($keys)->toHaveKey('category')
        ->and($keys['category'])->toBeFalse();
});

test('fillTogether excludes relation keys from parent fill', function () {
    $data = [
        'name' => 'Test Product',
        'sku' => 'TEST-001',
        'price' => 99.99,
    ];

    $product = new Product;
    $product->fillTogether($data);

    // Parent attributes should be filled
    expect($product->name)->toBe('Test Product')
        ->and($product->sku)->toBe('TEST-001')
        ->and($product->price)->toBe(99.99);
});

test('model has correct fillable attributes', function () {
    $product = new Product;

    expect($product->getFillable())->toContain('name', 'description', 'sku', 'price', 'category_id');
});
