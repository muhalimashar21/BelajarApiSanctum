<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Level extends Model
{
    use HasFactory;
    protected $table = 'tb_level';
    protected $primaryKey = 'level_id';
    public $timestamp = false;

    protected $fillable = [
        'level_id',
        'level_nama'
    ];

    protected $hidden = [
        'created_at',
        'update_at',
    ];
}
