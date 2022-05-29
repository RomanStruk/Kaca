<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShiftsTable extends Migration
{
    public function up()
    {
        Schema::create('shifts', function (Blueprint $table) {
            $table->uuid('id')->unique();
            $table->unsignedInteger('serial')->nullable();
            $table->unsignedBigInteger('balance')->default(0);
            $table->string('status')->default('CREATED');
            $table->timestampTz('opened_at')->nullable();
            $table->timestampTz('closed_at')->nullable();
            $table->timestampsTz();

            $table->uuid('cash_register_id');
            $table->uuid('cashier_id');

            $table->foreign('cashier_id')
                ->references('id')
                ->on('cashiers')
                ->onDelete('cascade');

            $table->foreign('cash_register_id')
                ->references('id')
                ->on('cash_registers')
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('shifts');
    }
}
