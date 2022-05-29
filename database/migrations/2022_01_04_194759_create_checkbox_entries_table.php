<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCheckboxEntriesTable extends Migration
{
    public function up()
    {
        Schema::create('checkbox_entries', function (Blueprint $table) {
            $table->id();
            $table->string('tag')->nullable();
            $table->string('type', 20);
            $table->longText('content');
            $table->timestampTz('created_at')->nullable();

            $table->index('type');
        });
    }

    public function down()
    {
        Schema::dropIfExists('checkbox_entries');
    }
}
