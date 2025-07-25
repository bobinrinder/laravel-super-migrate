<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('error_table', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('name'); // intentionally duplicate column to trigger error
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('error_table');
    }
};
