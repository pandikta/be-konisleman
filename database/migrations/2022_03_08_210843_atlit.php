<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Atlit extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('atlit', function (Blueprint $table) {
            $table->string('id_atlit', 32)->primary();
            $table->string('id_cabor')->references('id_cabor')->on('cabang_olahraga');
            $table->string('nama_atlit');
            $table->string('jabatan');
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
        Schema::dropIfExists('atlit');
    }
}
