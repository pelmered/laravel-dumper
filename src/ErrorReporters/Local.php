<?php

namespace Pelmered\LaravelDumper\ErrorReporters;

class Local implements ErrorReporter
{
    public static function generateExceptionID(): string
    {
        return substr(md5(uniqid('', true)), 0, 8);
    }
}
