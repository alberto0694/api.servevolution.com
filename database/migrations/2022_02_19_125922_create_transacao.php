<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransacao extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {    
        Schema::create('transacao', function (Blueprint $table) {
            $table->id();
            $table->integer('parcela_id');
            $table->float('valor_baixado');
            $table->boolean('ativo');
            $table->timestamps();

            $table->foreign('parcela_id')->references('id')->on('parcela');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transacao');
    }
}
