<?php

namespace Pelmered\LaravelDumper;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Pelmered\LaravelDumper\Commands\LaravelDumperCommand;

class LaravelDumperServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('laravel-dumper')
            ->hasConfigFile();
    }
}
