<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddTableForTestModel extends Migration
{
    public function up()
    {
        Schema::create('test_model', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('title', 100);
            $table->string('code')->unique();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('test_model');
    }
}
