<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCashiersTable extends Migration
{
    public function up()
    {
        Schema::create('cashiers', function (Blueprint $table) {
            $table->uuid('id')->unique();
            $table->string('full_name');
            $table->string('nin');
            $table->string('key_id');
            $table->string('signature_type');
            $table->text('access_token');
            $table->timestampTz('certificate_end')->nullable();
            $table->timestampsTz();
        });
    }

    public function down()
    {
        Schema::dropIfExists('cashiers');
    }
}
