<?php

namespace Pelmered\LaravelDumper\Tests;

use Illuminate\Database\Eloquent\Factories\Factory;
use Orchestra\Testbench\TestCase as Orchestra;
use Pelmered\LaravelDumper\LaravelDumperServiceProvider;
use Pelmered\LaravelDumper\Tests\Traits\TestHelpers;

class TestCase extends Orchestra
{
    use TestHelpers;

    protected function setUp(): void
    {
        parent::setUp();

        Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'Pelmered\\LaravelDumper\\Database\\Factories\\'.class_basename($modelName).'Factory'
        );
    }

    protected function getPackageProviders($app)
    {
        return [
            LaravelDumperServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        config()->set('database.default', 'testing');

        /*
        $migration = include __DIR__.'/../database/migrations/create_laravel-dumper_table.php.stub';
        $migration->up();
        */
    }
}
