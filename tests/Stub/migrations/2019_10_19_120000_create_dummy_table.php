<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDummyTable extends Migration
{

    public function up()
    {
        Schema::create('dummy', function(Blueprint $table) {
            $table->increments('id');
            $table->string('dummy_column');
        });
    }

    public function down()
    {
        
    }

}