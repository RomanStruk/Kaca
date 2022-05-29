<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCashRegistersTable extends Migration
{
    public function up()
    {
        Schema::create('cash_registers', function (Blueprint $table) {
            $table->uuid('id')->unique();
            $table->string('title');
            $table->string('address');
            $table->string('fiscal_number');
            $table->string('licence_key');
            $table->timestampsTz();
        });
    }

    public function down()
    {
        Schema::dropIfExists('cash_registers');
    }
}
