<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengumuman extends Model
{
    use HasFactory;
    protected $fillable = ['id_pengumuman', 'judul_pengumuman', 'isi_pengumuman', 'gambar', 'file', 'id_user'];
    protected $hidden = ['id'];
    protected $primaryKey = 'id_pengumuman';
    protected $keyType = 'string';
    protected $table = 'pengumuman';
}
