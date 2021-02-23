<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penelitian extends Model
{
    use HasFactory;

    protected $table = 'penelitian';
    protected $dates = ['deleted_at'];
    protected $fillable = [
        'user_id',
        'tanggal',
        'tahun',
        'judul',
        'ketua_peneliti',
        'kelompok_peneliti',
        'sumber_dana',
        'anggaran',
        'sisa_anggaran',
        'progres_pengeluaran',
        'progres_fisik'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function dataProgresFisik()
    {
        return $this->hasMany(ProgresFisik::class);
    }

    public function dataProgresPengeluaran()
    {
        return $this->hasMany(ProgresPengeluaran::class);
    }
}
