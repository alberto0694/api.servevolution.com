<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateValoresFuncionarios extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {    
        Schema::create('valores_funcionarios', function (Blueprint $table) {
            $table->id();
            $table->integer('tipo_servico_id');
            $table->integer('cliente_id');
            $table->integer('unidade_medida_id');
            $table->integer('funcionario_id');
            $table->float('valor');
            $table->boolean('ativo')->default(true);
            $table->timestamps();

            $table->foreign('tipo_servico_id')->references('id')->on('tipo_servico');
            $table->foreign('cliente_id')->references('id')->on('cliente');
            $table->foreign('unidade_medida_id')->references('id')->on('unidade_medida');
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
        Schema::dropIfExists('valores_funcionarios');
    }
}
