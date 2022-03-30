<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEndereco extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('endereco', function (Blueprint $table) {
            $table->id();
            $table->string('cep');
            $table->string('logradouro');
            $table->string('complemento');
            $table->string('pais');
            $table->string('bairro');
            $table->string('numero');
            $table->integer('estado_id');
            $table->integer('municipio_id');
            $table->boolean('ativo')->default(true);

            $table->foreign('estado_id')->references('id')->on('estado');
            $table->foreign('municipio_id')->references('id')->on('municipio');

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
        Schema::dropIfExists('endereco');
    }
}
