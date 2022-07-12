<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateParcela extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {        
        Schema::create('parcela', function (Blueprint $table) {
            $table->id();
            $table->integer('titulo_id');
            $table->float('valor_nominal');
            $table->float('valor_atualizado');
            $table->float('valor_baixado');
            $table->float('saldo');
            $table->timestamp('vencimento');
            $table->boolean('ativo');
            $table->timestamps();

            $table->foreign('titulo_id')->references('id')->on('titulo');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('parcela');
    }
}
