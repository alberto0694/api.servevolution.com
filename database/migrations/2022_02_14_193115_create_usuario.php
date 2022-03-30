<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsuario extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('usuario', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->integer('pessoa_id');
            $table->string('password');
            $table->string('remember_token')->nullable();
            $table->string('email_verified_at')->nullable();
            $table->boolean('ativo')->default(true);
            $table->timestamps();

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
        Schema::dropIfExists('usuario');
    }
}
