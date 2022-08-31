<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdemServicoStatus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ordem_servico_status', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ordem_servico_id')->nullable();
            $table->string('descricao');
            $table->boolean('ativo')->default(true);
            $table->timestamps();

            $table->foreign('ordem_servico_id')->references('id')->on('ordem_servico');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ordem_servico_status');
    }
}
