<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCashierFieldToUsersTable extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->uuid('cashier_id')->nullable();
            $table->foreign('cashier_id')
                ->references('id')
                ->on('cashiers')
                ->nullOnDelete();

            $table->uuid('cash_register_id')->nullable();
            $table->foreign('cash_register_id')
                ->references('id')
                ->on('cash_registers')
                ->nullOnDelete();
        });
    }

    public function down()
    {
        Schema::table('users',  function (Blueprint $table) {
            $table->dropForeign('users_cashier_id_foreign');
            $table->dropForeign('users_cash_register_id_foreign');
            $table->dropColumn(['cashier_id', 'cash_register_id']);
        });
    }
}
