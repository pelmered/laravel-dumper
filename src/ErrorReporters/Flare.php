<?php
namespace Pelmered\LaravelDumper\ErrorReporters;

use Pelmered\LaravelDumper\ErrorReporters\ErrorReporter;
use Pelmered\LaravelDumper\ErrorReporters\Local;

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
