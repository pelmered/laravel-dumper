<?php

namespace Pelmered\LaravelDumper\ErrorReporters;

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
