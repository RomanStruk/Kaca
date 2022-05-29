<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateActionsTable extends Migration
{
    public function up()
    {
        Schema::create('actions', function (Blueprint $table) {
            $table->id();
            $table->string('tag');
            $table->unsignedBigInteger('user_id');
            $table->uuid('target');
            $table->timestampTz('created_at');
        });
    }

    public function down()
    {
        Schema::dropIfExists('actions');
    }
}