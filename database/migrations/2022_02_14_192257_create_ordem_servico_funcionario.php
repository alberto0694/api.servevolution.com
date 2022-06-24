<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdemServicoFuncionario extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ordem_servico_funcionario', function (Blueprint $table) {
            $table->id();
            $table->integer('funcionario_id');
            $table->integer('ordem_servico_id');
            $table->float('valor_servico')->default(0);
            $table->timestamps();

            $table->foreign('funcionario_id')->references('id')->on('funcionario');
            $table->foreign('ordem_servico_id')->references('id')->on('ordem_servico');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ordem_servico_funcionario');
    }
}
