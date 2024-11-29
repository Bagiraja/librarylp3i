<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rak extends Model
{


    use HasFactory;

    protected $table = 'raks';  // Ensure this matches your database table name
    protected $fillable = ['nama_rak'];

    // Optional: Add validation
    public static function getNamaRaks()
    {
        return self::pluck('nama_rak');
    }
}
