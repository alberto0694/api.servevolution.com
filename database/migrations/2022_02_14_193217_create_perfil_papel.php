<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePerfilPapel extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('perfil_papel', function (Blueprint $table) {
            $table->id();
            $table->integer('perfil_id');
            $table->integer('papel_id');
            $table->timestamps();

            $table->foreign('perfil_id')->references('id')->on('perfil');
            $table->foreign('papel_id')->references('id')->on('papel');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('perfil_papel');
    }
}
