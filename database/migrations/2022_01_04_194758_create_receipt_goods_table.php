<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReceiptGoodsTable extends Migration
{
    public function up()
    {
        Schema::create('receipt_goods', function (Blueprint $table) {
            $table->uuid('receipt_id');
            $table->id();
            $table->string('name');
            $table->string('code');
            $table->unsignedBigInteger('price')->default(0);
            $table->unsignedInteger('quantity')->default(1000);
            $table->boolean('is_return')->default(false);
            $table->string('related_local_good_id')->nullable();
            $table->timestampsTz();

            $table->foreign('receipt_id')
                ->references('id')
                ->on('receipts')
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('receipt_goods');
    }
}
