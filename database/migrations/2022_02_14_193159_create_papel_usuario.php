<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePapelUsuario extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('papel_usuario', function (Blueprint $table) {
            $table->id();
            $table->integer('papel_id');
            $table->integer('usuario_id');
            $table->timestamps();

            $table->foreign('papel_id')->references('id')->on('papel');
            $table->foreign('usuario_id')->references('id')->on('usuario');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('papel_usuario');
    }
}
