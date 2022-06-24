<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFuncionarioTipoServico extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('funcionario_tipo_servico', function (Blueprint $table) {
            $table->id();
            $table->integer('tipo_servico_id');
            $table->integer('funcionario_id');
            $table->integer('unidade_medida_id');
            $table->float('valor_cobrado')->default(0);
            $table->timestamps();

            $table->foreign('tipo_servico_id')->references('id')->on('tipo_servico');
            $table->foreign('funcionario_id')->references('id')->on('funcionario');
            $table->foreign('unidade_medida_id')->references('id')->on('unidade_medida');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('funcionario_tipo_servico');
    }
}
