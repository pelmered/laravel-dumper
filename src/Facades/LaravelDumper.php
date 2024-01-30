<?php

namespace Pelmered\LaravelDumper\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Pelmered\LaravelDumper\LaravelDumper
 */
class LaravelDumper extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Pelmered\LaravelDumper\LaravelDumper::class;
    }
}
