<?php

namespace Pelmered\LaravelDumper\Tests\Traits;

use Pelmered\LaravelDumper\LaravelDumperServiceProvider;

use function Orchestra\Testbench\workbench_path;

trait UsesDatabase
{
    /**
     * Define database migrations.
     *
     * @return void
     */
    protected function defineDatabaseMigrations()
    {
        $this->loadMigrationsFrom(workbench_path('database/migrations'));
    }

    /**
     * add the package provider
     *
     *
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [LaravelDumperServiceProvider::class];
    }

    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        // Setup default database to use sqlite :memory:
        $app['config']->set('database.default', 'testbench');
        $app['config']->set('database.connections.testbench', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);
    }
}
