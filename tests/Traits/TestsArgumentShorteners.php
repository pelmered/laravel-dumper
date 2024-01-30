<?php
namespace Pelmered\LaravelDumper\Tests\Traits;

use Pelmered\LaravelDumper\LaravelDumper;

trait TestsArgumentShorteners
{

    public function assertShortenedArgument($expected, $argument, string $expectedType = null): void
    {
        $shortenedArgument = LaravelDumper::shortenArgument($argument);


        //$value = array_values($shortenedArgument);
        //dd($shortenedArgument, $value, $expected, $argument);

        //dump(LaravelDumper::getCaller());
        //dump($expected, $shortenedArgument);
        $this->assertEquals($expected, $shortenedArgument);

        if ($expectedType) {
            $type = array_keys($shortenedArgument)[0];

            preg_match('/.+\((.*)\)/', $type, $matches);

            if (count($matches) > 1) {
                $type = $matches[1];
            }

            $this->assertEquals($expectedType, $type);
        }
    }
}
