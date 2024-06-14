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
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('type_id');
            $table->unsignedBigInteger('area_id');
            $table->boolean('representation');
            $table->string('name')->nullable();
            $table->string('lasta_name')->nullable();
            $table->string('first_name')->nullable();
            $table->string('dni')->nullable();
            $table->string('ruc')->nullable();
            $table->string('empresa')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('addres')->nullable();
            $table->string('code');
            $table->date('date');
            $table->string('folio');
            $table->string('asunto');
            $table->string('file');
            $table->timestamps();
            $table->foreign('type_id')->references('id')->on('types');
            $table->foreign('area_id')->references('id')->on('types');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
