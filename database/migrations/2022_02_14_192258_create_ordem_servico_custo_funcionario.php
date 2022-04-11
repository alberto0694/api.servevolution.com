<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdemServicoCustoFuncionario extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ordem_servico_custo_funcionario', function (Blueprint $table) {
            $table->id();
            $table->integer('ordem_servico_id');
            $table->integer('funcionario_id');
            $table->integer('tipo_custo_servico_id');
            $table->boolean('ativo')->default(true);
            $table->timestamps();

            $table->foreign('ordem_servico_id')->references('id')->on('ordem_servico');
            $table->foreign('tipo_custo_servico_id')->references('id')->on('tipo_custo_servico');
            $table->foreign('funcionario_id')->references('id')->on('funcionario');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ordem_servico_custo_funcionario');
    }
}
