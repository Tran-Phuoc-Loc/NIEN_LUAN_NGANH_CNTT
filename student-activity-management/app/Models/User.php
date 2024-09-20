<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'student_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Mối quan hệ với bảng students 
    public function student()
    {
        return $this->hasOne(Student::class, 'student_id', 'student_id');
    }

    public function registrations()
    {
        return $this->hasMany(Registration::class);
    }
}
