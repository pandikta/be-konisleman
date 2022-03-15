<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Landing extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('landing', function (Blueprint $table) {
            $table->string('id_landing', 32)->primary();
            $table->json('gambar_landing');
            $table->string('judul_agenda');
            $table->date('tgl_agenda');
            $table->string('gambar_agenda');
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
        Schema::dropIfExists('tentang_kami');
    }
}
