<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTabelaPrecoCliente extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tabela_preco_cliente', function (Blueprint $table) {
            $table->id();
            $table->integer('tabela_preco_id');
            $table->integer('cliente_id');
            $table->timestamps();

            $table->foreign('tabela_preco_id')->references('id')->on('tabela_preco');
            $table->foreign('cliente_id')->references('id')->on('cliente');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tabela_preco_cliente');
    }
}
