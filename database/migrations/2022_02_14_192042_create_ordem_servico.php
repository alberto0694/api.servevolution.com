<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdemServico extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ordem_servico', function (Blueprint $table) {
            $table->id();
            $table->longText('descricao')->nullable();
            $table->string('status')->nullable();
            $table->date('data')->nullable();
            $table->time('hora')->nullable();
            $table->integer('tipo_servico_id');
            $table->integer('cliente_id');
            $table->float('valor')->nullable();
            $table->boolean('ativo')->default(true);
            $table->timestamps();

            $table->foreign('tipo_servico_id')->references('id')->on('tipo_servico');
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
        Schema::dropIfExists('ordem_servico');
    }
}
