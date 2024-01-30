<?php

use Pelmered\LaravelDumper\ArgumentShorteners\ArrayShortener;
use Pelmered\LaravelDumper\ArgumentShorteners\CarbonShortener;
use Pelmered\LaravelDumper\ArgumentShorteners\CollectionShortener;
use Pelmered\LaravelDumper\ArgumentShorteners\ModelShortener;
use Pelmered\LaravelDumper\ArgumentShorteners\ObjectShortener;
use Pelmered\LaravelDumper\ArgumentShorteners\RequestShortener;
use Pelmered\LaravelDumper\ArgumentShorteners\ScalarShortener;
use Pelmered\LaravelDumper\ArgumentShorteners\StringableShortener;

return [

    'enabled' => env('LARAVEL_DUMPER_ENABLED', true),

    'show_type_info' => true,

    /**
     * Create trackable exception IDs for external services
     *
     * Possiböe vaöues: false, 'sentry', 'flare', or a custom class
     */
    'error_reporter' => false,

    /**
     * Shorteners are used to shorten the arguments passed to the dump functions
     * The order of the classes matter. The first class that returns true for
     * shouldRun() will be used to shorten the argument.
     */
    'shorteners' => [
        ScalarShortener::class,
        CollectionShortener::class,
        CarbonShortener::class,
        ModelShortener::class,
        RequestShortener::class,
        StringableShortener::class,
        ObjectShortener::class,
        ArrayShortener::class,
    ],


        /*
    'dumpers' => [
            'dump' => [
                'enabled' => env('LARAVEL_DUMPER_DUMP_ENABLED', true),
                'method' => 'dump',
                'class' => \Pelmered\LaravelDumper\LaravelDumper::class,
            ],
            'dd' => [
                'enabled' => env('LARAVEL_DUMPER_DD_ENABLED', true),
                'method' => 'dd',
                'class' => \Pelmered\LaravelDumper\LaravelDumper::class,
            ],
            'dump_and_die' => [
                'enabled' => env('LARAVEL_DUMPER_DUMP_AND_DIE_ENABLED', true),
                'method' => 'dumpAndDie',
                'class' => \Pelmered\LaravelDumper\LaravelDumper::class,
            ],
        ],

    'exception' => [
            'enabled' => env('LARAVEL_DUMPER_EXCEPTION_ENABLED', true),
            'class' => \Pelmered\LaravelDumper\LaravelDumper::class,
            'method' => 'exception',
        ],

    'exception_handler' => [
            'enabled' => env('LARAVEL_DUMPER_EXCEPTION_HANDLER_ENABLED', true),
            'class' => \Pelmered\LaravelDumper\LaravelDumper::class,
            'method' => 'exceptionHandler',
        ],

    'error_handler' => [
            'enabled' => env('LARAVEL_DUMPER_ERROR_HANDLER_ENABLED', true),
            'class' => \Pelmered\LaravelDumper\LaravelDumper::class,
            'method' => 'errorHandler',
        ],

    'shutdown_handler' => [
            'enabled' => env('LARAVEL_DUMPER_SHUTDOWN_HANDLER_ENABLED', true),
            'class' => \Pelmered\LaravelDumper\LaravelDumper::class,
            'method' => 'shutdownHandler',
        ],

    */





];
