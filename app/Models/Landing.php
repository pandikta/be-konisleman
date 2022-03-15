<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Landing extends Model
{
    use HasFactory;
    protected $fillable = ['id_landing', 'gambar_landing', 'judul_agenda', 'tgl_agenda', 'gambar_agenda'];
    protected $hidden = ['id'];
    protected $primaryKey = 'id_landing';
    protected $keyType = 'string';
    protected $table = 'landing';
}
