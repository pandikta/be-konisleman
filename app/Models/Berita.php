<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Berita extends Model
{
    use HasFactory;
    protected $fillable = ['id_berita', 'judul_berita', 'isi_berita', 'id_cabor', 'gambar_berita', 'id_user'];
    protected $hidden = ['id'];
    protected $primaryKey = 'id_berita';
    protected $keyType = 'string';
    protected $table = 'berita';
}
