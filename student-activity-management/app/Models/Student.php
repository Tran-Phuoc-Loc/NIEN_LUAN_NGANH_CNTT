<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'name',
        'email',
        'phone',
        'class',
        'department',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id'); // Liên kết đến bảng users qua user_id
    }

    public function registrations()
    {
        return $this->hasMany(Registration::class, 'user_id', 'id');
    }
}
