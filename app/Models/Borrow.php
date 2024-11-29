<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Borrow extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'book_id',
        'name',
        'status',
        'approved_at',
        'rejected_at',
        'returned_at',
        'due_date',
        'notes'
    ];

    protected $dates = [
        'approved_at',
        'rejected_at',
        'returned_at',
        'due_date'
    ];

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function fines()
    {
        return $this->hasMany(Fine::class);
    }
}
