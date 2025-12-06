<?php

namespace Denizgolbas\EloquentSaveTogether\Tests;

use Denizgolbas\EloquentSaveTogether\EloquentSaveTogetherServiceProvider;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

abstract class TestCase extends OrchestraTestCase
{
    use LazilyRefreshDatabase;

    protected function getPackageProviders($app): array
    {
        return [
            EloquentSaveTogetherServiceProvider::class,
        ];
    }

    protected function defineDatabaseMigrations(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/database/migrations');
    }

    protected function defineEnvironment($app): void
    {
        $app['config']->set('database.default', 'testing');
        $app['config']->set('database.connections.testing', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);
    }
}
