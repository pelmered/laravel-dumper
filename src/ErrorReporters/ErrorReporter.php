<?php
namespace Pelmered\LaravelDumper\ErrorReporters;

interface ErrorReporter
{
    public static function generateExceptionID(): string;
}
