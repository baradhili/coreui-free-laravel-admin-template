<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('form_field', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
            $table->string('name');
            $table->string('type');
            $table->boolean('browse');
            $table->boolean('read');
            $table->boolean('edit');
            $table->boolean('add');
            $table->string('relation_table')->nullable();
            $table->string('relation_column')->nullable();
            $table->integer('form_id')->unsigned();
            $table->string('column_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('form_field');
    }
};
