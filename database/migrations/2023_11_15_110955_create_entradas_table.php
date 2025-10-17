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
        Schema::create('entradas', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('folio');
            $table->string('numero_oficio');
            $table->text('asunto');
            $table->date('fecha_termino')->nullable();
            $table->foreignId('destinatario')->nullable()->constrained()->references('id')->on('oficinas');
            $table->foreignId('dependencia_id')->nullable()->constrained();
            $table->foreignId('oficina_id')->nullable()->constrained();
            $table->foreignId('creado_por')->nullable()->constrained()->references('id')->on('users');
            $table->foreignId('actualizado_por')->nullable()->constrained()->references('id')->on('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('entradas');
    }
};
