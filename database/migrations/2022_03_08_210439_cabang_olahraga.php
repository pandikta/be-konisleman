<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CabangOlahraga extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cabang_olahraga', function (Blueprint $table) {
            $table->string('id_cabor', 32)->primary();
            $table->string('nama_cabor');
            $table->string('icon_cabor');
            $table->string('persatuan_cabor');
            $table->string('alamat_cabor');
            $table->string('no_telp');
            $table->string('email');
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
        Schema::dropIfExists('cabang_olahraga');
    }
}
