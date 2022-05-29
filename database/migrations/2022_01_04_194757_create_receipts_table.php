<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReceiptsTable extends Migration
{
    public function up()
    {
        Schema::create('receipts', function (Blueprint $table) {
            $table->uuid('id')->unique();
            $table->string('fiscal_code')->unique()->nullable();
            $table->string('type')->nullable();
            $table->unsignedInteger('serial')->nullable();
            $table->string('status')->default('CREATED');
            $table->text('delivery');
            $table->unsignedBigInteger('total_sum')->nullable();
            $table->unsignedBigInteger('total_payment')->nullable();
            $table->string('order_id')->nullable()->index();
            $table->text('reverse_compatibility_data')->nullable();
            $table->timestampsTz();

            $table->uuid('shift_id');
            $table->uuid('related_receipt_id')->nullable();

            $table->foreign('shift_id')
                ->references('id')
                ->on('shifts')
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('receipts');
    }
}
