<?php
namespace Pelmered\LaravelDumper\ErrorReporters;

use Pelmered\LaravelDumper\ErrorReporters\ErrorReporter;
use Pelmered\LaravelDumper\ErrorReporters\Local;

class Sentry implements ErrorReporter
{
    public static function generateExceptionID(): string
    {
        if (! app()->bound('sentry')) {
            return Local::generateExceptionID();
        }

        return app('sentry')->getLastEventId();
    }


}
