<?php
namespace Pelmered\LaravelDumper\Tests\Shorteners;

use Illuminate\Http\Request;
use Pelmered\LaravelDumper\DataTypes\ShortendArgument;
use Pelmered\LaravelDumper\LaravelDumper;
use Pelmered\LaravelDumper\Tests\TestCase;
use Pelmered\LaravelDumper\Tests\TestClasses\ClassWithToString;
use Pelmered\LaravelDumper\Tests\TestClasses\ComplexStdClass;
use Pelmered\LaravelDumper\Tests\TestClasses\StdClass;
use Pelmered\LaravelDumper\Tests\Traits\TestsArgumentShorteners;

class ShortenerTest extends TestCase
{
    use TestsArgumentShorteners;

    /*
    public function testShortenModel()
    {
        $model = new \Illuminate\Database\Eloquent\Model();
        $this->assertShortenedArgument($model, $model->toArray());
    }
    */
    function testShortenArray()
    {
        $array = [
            'foo' => ['string (scalar)' => 'bar'],
            'baz' => ['string (scalar)' => 'qux'],
        ];
        $this->assertShortenedArgument($array, $array, 'array');
    }
    function testShortenStringable()
    {
        $stringable = new \Illuminate\Support\Stringable('foo');
        $this->assertShortenedArgument($stringable, 'foo');

        $this->assertShortenedArgument('foo', new ClassWithToString(), 'stringable');
    }
    function testShortenString()
    {
        $string = 'foo';
        $this->assertShortenedArgument($string, $string, 'scalar');
    }
    function testShortenRequest()
    {
        $request = Request::create(
            uri: 'https://example.com/api/v1/shortener',
            method: 'POST',
            parameters: [
                'foo' => 'bar',
                'baz' => 'qux'
            ],
            server: [
                'REQUEST_HOST' => 'example.com',
                'REQUEST_URI' => 'https://example.com/api/v1/shortener',
                'REQUEST_METHOD' => 'POST',
                'CONTENT_TYPE' => 'application/json'
            ],
            content: json_encode([
                'foo' => 'bar',
                'baz' => 'qux',
            ]),
        );

        $this->assertShortenedArgument(
            [
                'uri' => $request->getUri(),
                'method' => $request->getMethod(),
                'headers' => $request->headers->all(),
                'body' => $request->toArray(),
            ],
            $request,
            'request'
        );
    }
    function testShortenInt()
    {
        $int = 1;
        $this->assertShortenedArgument($int, $int, 'scalar');
    }
    function testShortenFloat()
    {
        $float = 1.1;
        $this->assertShortenedArgument($float, $float, 'scalar');
    }
    function testShortenBool()
    {
        $bool = true;
        $this->assertShortenedArgument($bool, $bool, 'scalar');
    }
    function testShortenNull()
    {
        $null = null;
        $this->assertShortenedArgument(null, $null, 'scalar');
    }
    function testShortenObject()
    {
        $object = new StdClass();

        $this->assertShortenedArgument(
            [
                'foo' => ['string (scalar)' => 'bar'],
                'baz' => ['string (scalar)' => 'qux'],
                'baz2 (array)' => ['array' => [0 => 'qux']],
            ],
            $object,
            'object',
        );

    }
    function testShortenCollection()
    {
        $collection = new \Illuminate\Support\Collection([
            'foo' => 'bar',
            'baz' => 'qux'
        ]);
        $this->assertShortenedArgument($collection->all(), $collection, 'Collection');
    }
    function testShortenCarbon()
    {
        $carbon = new \Carbon\Carbon();
        $this->assertShortenedArgument($carbon->toDateTimeString(), $carbon, 'Carbon');
    }

    /*
    function testShortenModelWithAttributes()
    {
        $model = new \Illuminate\Database\Eloquent\Model();
        $model->foo = 'bar';
        $model->baz = 'qux';
        $this->assertShortenedArgument($model, 'Illuminate\Database\Eloquent\Model', $model->getAttributes());
    }
    function testShortenModelWithAttributesAndRelations()
    {
        $model = new \Illuminate\Database\Eloquent\Model();
        $model->foo = 'bar';
        $model->baz = 'qux';
        $model->setRelation('foo', new \Illuminate\Database\Eloquent\Model());
        $model->setRelation('bar', new \Illuminate\Database\Eloquent\Model());
        $this->assertShortenedArgument($model, 'Illuminate\Database\Eloquent\Model', $model->getAttributes());
    }
    function testShortenModelWithAttributesAndRelationsAndCollection()
    {
        $model = new \Illuminate\Database\Eloquent\Model();
        $model->foo = 'bar';
        $model->baz = 'qux';
        $model->setRelation('foo', new \Illuminate\Support\Collection(['foo' => 'bar', 'baz' => 'qux']));
        $model->setRelation('bar', new \Illuminate\Support\Collection(['foo' => 'bar', 'baz' => 'qux']));
        $this->assertShortenedArgument($model, 'Illuminate\Database\Eloquent\Model', $model->getAttributes());
    }
    function testShortenModelWithAttributesAndRelationsAndCollectionAndModel()
    {
        $model = new \Illuminate\Database\Eloquent\Model();
        $model->foo = 'bar';
        $model->baz = 'qux';
        $model->setRelation('foo', new \Illuminate\Support\Collection(['foo' => 'bar', 'baz' => 'qux']));
        $model->setRelation('bar', new \Illuminate\Support\Collection(['foo' => 'bar', 'baz' => 'qux']));
        $model->setRelation('baz', new \Illuminate\Database\Eloquent\Model());
        $model->setRelation('qux', new \Illuminate\Database\Eloquent\Model());
        $this->assertShortenedArgument($model, 'Illuminate\Database\Eloquent\Model', $model->getAttributes());
    }
    */

    public function testShortendArgumentSetsTypeIfNotProvided()
    {
        $shortendArgument = new ShortendArgument(
            name: 'array',
            value: ['qux'],
            originalValue: ['qux'],
        );

        self::assertEquals('array', $shortendArgument->type);
    }

    public function testShortenArgumentLimitsDepthAsSpecified()
    {
        $object = [
            'test1' => [
                'test2' => [
                    'test3' => [
                        'test4' => [
                            'test5' => 'data',
                        ],
                        'test4_' => [
                            'test5' => 'data',
                        ]
                    ],
                ],
            ]
        ];

        $shortenedArgument = LaravelDumper::shortenArgument($object, 4);
        $this->assertEquals('[1]', $shortenedArgument['test1']['test2']['test3']['test4']);

        $shortenedArgument = LaravelDumper::shortenArgument($object); // 3 is default
        $this->assertEquals('[2]', $shortenedArgument['test1']['test2']['test3']);

        $shortenedArgument = LaravelDumper::shortenArgument($object, 2);
        $this->assertEquals('[1]', $shortenedArgument['test1']['test2']);

        $shortenedArgument = LaravelDumper::shortenArgument($object, 1);
        $this->assertEquals('[1]', $shortenedArgument['test1']);

        $shortenedArgument = LaravelDumper::shortenArgument($object, 0);
        $this->assertEquals('[1]', $shortenedArgument);
    }

}
