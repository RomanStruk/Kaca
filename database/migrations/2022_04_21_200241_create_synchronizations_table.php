<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSynchronizationsTable extends Migration
{
    public function up()
    {
        Schema::create('synchronizations', function (Blueprint $table) {
            $table->id();
            $table->uuid('target');
            $table->enum('status', ['CREATED', 'PROCESSING', 'DONE', 'ERROR']);
            $table->timestampsTz();
        });
    }

    public function down()
    {
        Schema::dropIfExists('synchronizations');
    }
}