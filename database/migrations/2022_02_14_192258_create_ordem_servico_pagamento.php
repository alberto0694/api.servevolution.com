<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdemServicoPagamento extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ordem_servico_pagamento', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ordem_servico_funcionario_id');
            $table->boolean('ativo')->default(true);
            $table->timestamps();

            $table->foreign('ordem_servico_funcionario_id')->references('id')->on('ordem_servico_funcionario');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ordem_servico_pagamento');
    }
}
