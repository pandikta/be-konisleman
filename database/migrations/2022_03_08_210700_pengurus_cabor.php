<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class PengurusCabor extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pengurus_cabor', function (Blueprint $table) {
            $table->string('id_pengurus_cabor', 32)->primary();
            $table->string('id_cabor')->references('id_cabor')->on('cabang_olahraga');
            $table->string('nama_pengurus');
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
        Schema::dropIfExists('pengurus_cabor');
    }
}
