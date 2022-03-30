<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdemServicoCliente extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ordem_servico_cliente', function (Blueprint $table) {
            $table->id();
            $table->integer('cliente_id');
            $table->integer('ordem_servico_id');
            $table->timestamps();

            $table->foreign('cliente_id')->references('id')->on('cliente');
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
        Schema::dropIfExists('ordem_servico_cliente');
    }
}
