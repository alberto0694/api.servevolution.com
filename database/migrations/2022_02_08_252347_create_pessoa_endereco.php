<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePessoaEndereco extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pessoa_endereco', function (Blueprint $table) {
            $table->id();
            $table->integer('endereco_id');
            $table->integer('pessoa_id');
            $table->timestamps();

            $table->foreign('endereco_id')->references('id')->on('endereco');
            $table->foreign('pessoa_id')->references('id')->on('pessoa');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pessoa_endereco');
    }
}
