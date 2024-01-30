<?php

namespace Pelmered\LaravelDumper\Tests;

use Pelmered\LaravelDumper\DataTypes\TraceFrame;
use Pelmered\LaravelDumper\LaravelDumper;
use Pelmered\LaravelDumper\Tests\TestClasses\StdClass;
use Pelmered\LaravelDumper\Tests\TestClasses\TestModel;

class GetCallerTest extends TestCase
{
    public function calledMethod(...$args)
    {
        return LaravelDumper::getCaller();
    }

    public function testCallerOutput(): void
    {
        $caller = $this->calledMethod(new TestModel(), new StdClass(), false, 'test');

        $this->assertNotNull($caller);
        $this->assertInstanceOf(TraceFrame::class, $caller);

        $this->assertArrayStructure(
            [
                'file', 'line', 'class', 'method', 'arguments',
            ],
            $caller->toArray()
        );

        $this->assertEquals('/Users/peter/Projects/laravel-dumper/tests/GetCallerTest.php', $caller->file);
        $this->assertIsInt($caller->line);
        $this->assertEquals('Pelmered\LaravelDumper\Tests\GetCallerTest', $caller->class);
        $this->assertEquals('testCallerOutput', $caller->method);
    }
}
