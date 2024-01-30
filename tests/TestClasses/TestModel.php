<?php

namespace Pelmered\LaravelDumper\Tests\TestClasses;

class TestModel extends \Illuminate\Database\Eloquent\Model
{
    protected $table = 'test_model';

    protected $guarded = [];
}
