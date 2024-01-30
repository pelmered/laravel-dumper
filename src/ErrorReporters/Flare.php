<?php

namespace Pelmered\LaravelDumper\ErrorReporters;

class Flare implements ErrorReporter
{
    public static function generateExceptionID(): string
    {
        $exceptionID = Local::generateExceptionID();

        if (app()->bound('flare')) {
            app('flare')->context('ExceptionID', $exceptionID);
        }

        return $exceptionID;
    }
}
