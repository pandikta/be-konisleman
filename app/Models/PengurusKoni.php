<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengurusKoni extends Model
{
    use HasFactory;
    protected $fillable = ['id_pengurus_koni', 'nama', 'jabatan', 'foto'];
    protected $hidden = ['id'];
    protected $primaryKey = 'id_pengurus_koni';
    protected $keyType = 'string';
    protected $table = 'pengurus_koni';
}
