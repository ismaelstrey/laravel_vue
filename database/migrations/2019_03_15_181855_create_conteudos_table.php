<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConteudosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('conteudos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('titulo');
            $table->longText('texto');
            $table->string('img');
            $table->string('link');
            $table->dateTime('date');
            $table->timestamps();
        });
        Schema::table('comentarios', function($table) {
            $table->unsignedBigInteger('conteudo_id');
            $table->foreign('conteudo_id')->references('id')->on('conteudos')->onDelete('cascade');

        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('conteudos');
    }
}
