<?php

namespace Pelmered\LaravelDumper\Tests\Shorteners;

use Illuminate\Support\Arr;
use Pelmered\LaravelDumper\Tests\TestClasses\TestModel;
use Pelmered\LaravelDumper\Tests\Traits\TestsArgumentShorteners;
use Pelmered\LaravelDumper\Tests\Traits\UsesDatabase;

class ModelShortenerTest extends \Orchestra\Testbench\TestCase
{
    use TestsArgumentShorteners;
    use UsesDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->model = TestModel::create([
            'name' => 'Name',
            'title' => 'Titel',
            'code' => 'Code',
        ]);
    }

    public function test_verify_model_created()
    {
        $this->assertNotNull($this->model);
        $this->assertEquals(1, TestModel::count());
    }

    public function testShortenModel()
    {
        $array = Arr::mapWithKeys($this->model->toArray(), function ($value, $key) {
            return [$key.' ('.gettype($value).')' => $value];
        });

        dump($array);

        $this->assertShortenedArgument($array, $this->model);
    }
}
