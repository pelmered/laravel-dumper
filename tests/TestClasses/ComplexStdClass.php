<?php

namespace Pelmered\LaravelDumper\Tests\TestClasses;

class ComplexStdClass
{
    public string $foo = 'bar';
    private string $baz = 'qux';
    public array $baz2 = [
        'qux'
    ];
    public StdClass $class;
    public object $deepClass;

    public function __construct()
    {
        $this->baz2[] = $this;

        $this->class = new StdClass();

        $this->deepClass = new class( new StdClass() ) {
            public $deepClass;

            public function __construct(public StdClass $class)
            {
                $this->deepClass = new class($class) {
                    public function __construct(public StdClass $class)
                    {
                    }
                };
            }
        };
    }
}
