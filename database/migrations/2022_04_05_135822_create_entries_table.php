<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('entries', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('folio');
            $table->string('numero_oficio');
            $table->text('asunto');
            $table->date('fecha_termino')->nullable();
            $table->foreignId('destinatario_id')->nullable()->constrained()->references('id')->on('offices');
            $table->foreignId('origen_id')->nullable()->constrained()->references('id')->on('dependencies');
            $table->foreignId('office_id')->nullable()->constrained();
            $table->foreignId('created_by')->nullable()->constrained()->references('id')->on('users');
            $table->foreignId('updated_by')->nullable()->constrained()->references('id')->on('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('entries');
    }
};
