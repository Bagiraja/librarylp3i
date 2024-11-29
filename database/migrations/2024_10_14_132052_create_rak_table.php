<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRakTable extends Migration
{
    public function up()
    {
        Schema::create('raks', function (Blueprint $table) {
            $table->id();
            $table->string('nama_rak');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('raks');
    }
}
