<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFuncionarioEndereco extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('funcionario_endereco', function (Blueprint $table) {
            $table->id();
            $table->integer('endereco_id');
            $table->integer('funcionario_id');
            $table->timestamps();

            $table->foreign('endereco_id')->references('id')->on('endereco');
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
        Schema::dropIfExists('funcionario_endereco');
    }
}
