<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFuncionario extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('funcionario', function (Blueprint $table) {
            $table->id();
            $table->integer('pessoa_id');
            $table->string('cpf')->nullable();
            $table->string('rg')->nullable();
            $table->string('orgao_emissor')->nullable();
            $table->string('uf_emissor')->nullable();
            $table->string('sexo');
            $table->timestamp(('data_admissao'))->nullable();
            $table->timestamp(('data_demissao'))->nullable();
            $table->string('referencia_id')->nullable();
            $table->boolean('ativo')->default(true);
            $table->timestamps();


            $table->foreign('pessoa_id')->references('id')->on('pessoa');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('funcionario');
    }
}

