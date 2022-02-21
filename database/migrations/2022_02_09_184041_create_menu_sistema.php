<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMenuSistema extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('menu_sistema', function (Blueprint $table) {            
            $table->id();
            $table->string('titulo');
            $table->integer('nivel');
            $table->string('icone');
            $table->string('iconeaux');
            $table->integer('papel_id');    
            $table->string('menu_pai_id')->nullable();    
            $table->boolean('excluido')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('menu_sistema');
    }
}
