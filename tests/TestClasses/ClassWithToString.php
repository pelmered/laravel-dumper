<?php

namespace Pelmered\LaravelDumper\Tests\TestClasses;

class ClassWithToString
{
    public function __toString()
    {
        return 'foo';
    }
}
