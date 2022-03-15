<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TentangKami extends Model
{
    use HasFactory;
    protected $fillable = ['id_tentang_kami', 'visi', 'misi', 'struktur_org'];
    protected $hidden = ['id'];
    protected $primaryKey = 'id_tentang_kami';
    protected $keyType = 'string';
    protected $table = 'tentang_kami';
}
