<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReceiptPaymentsTable extends Migration
{
    public function up()
    {
        Schema::create('receipt_payments', function (Blueprint $table) {
            $table->id();
            $table->uuid('receipt_id');

            $table->enum('type', ['CASH', 'CARD', 'CASHLESS'])->default('CASHLESS');
            $table->unsignedBigInteger('value');
            $table->string('label')->default('Картка');

            $table->foreign('receipt_id')
                ->references('id')
                ->on('receipts')
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('receipt_payments');
    }
}