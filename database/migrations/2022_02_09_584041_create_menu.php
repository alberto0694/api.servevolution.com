<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMenu extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('menu', function (Blueprint $table) {
            $table->id();
            $table->string('titulo');
            $table->integer('nivel');
            $table->string('icone');
            $table->string('icone_aux');
            $table->unsignedBigInteger('papel_id');
            $table->string('rota')->nullable();
            $table->string('menu_pai_id')->nullable();
            $table->boolean('ativo')->default(true);
            $table->timestamps();

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
        Schema::dropIfExists('menu');
    }
}
