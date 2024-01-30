<?php
namespace Pelmered\LaravelDumper\Tests;

use Illuminate\Support\Arr;
use Pelmered\LaravelDumper\LaravelDumper;
use Pelmered\LaravelDumper\Tests\TestClasses\StdClass;
use Pelmered\LaravelDumper\Tests\TestClasses\TestModel;

class TraceTest extends TestCase
{

    public function tracedMethod(...$args)
    {
        return LaravelDumper::trace(4);
    }

    public function testTraceOutput(): void
    {
        $trace = $this->tracedMethod(new TestModel(), new StdClass(), false, 'test');

        $this->assertNotNull($trace);
        $this->assertIsArray($trace);

        $this->assertArrayStructure(
            [
                '*' => [
                    'file', 'line', 'class', 'method', 'arguments',
                ]
            ],
            Arr::map($trace, function ($frame) {
                return $frame->toArray();
            })
        );

        $this->assertEquals('/Users/peter/Projects/laravel-dumper/tests/TraceTest.php', $trace[0]->file);
        $this->assertEquals('Pelmered\LaravelDumper\Tests\TraceTest', $trace[0]->class);
        $this->assertEquals('tracedMethod', $trace[0]->method);
    }
}
