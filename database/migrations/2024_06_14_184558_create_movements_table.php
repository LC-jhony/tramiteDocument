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
        Schema::create('movements', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('document_id');
            $table->unsignedBigInteger('area_origen_id');
            $table->unsignedBigInteger('destination_area_id');
            $table->dateTime('date_movement');
            $table->text('description');
            $table->enum('status', ['Aceptado', 'Proceso', 'Rechazado', 'finalizado']);
            //$table->unsignedBigInteger('user_id');
            $table->string('mov_file')->nullable();
            $table->text('mov_description_origen');
            $table->timestamps();
            // $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('document_id')->references('id')->on('documents')->onDelete('cascade');
            $table->foreign('area_origen_id')->references('id')->on('areas')->onDelete('cascade');
            $table->foreign('destination_area_id')->references('id')->on('areas')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('movements');
    }
};
