<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class DropDummyTable extends Migration
{

    public function up()
    {
        Schema::dropIfExists('dummy');
    }

    public function down()
    {
        
    }

}