<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    use HasFactory;
    
    // Ngăn chặn cuộc tấn công "gán giá trị hàng loạt" chỉ cho phép những giá trị trong $fillable
    protected $fillable = [
        'name',
        'description',
        'date',
        'location',
        'registration_start',
        'registration_end',
        'benefits', 
        'is_hidden',
        'max_participants'
    ];

    // Ép kiểu các thuộc tính thành datetime
    protected $casts = [
        'date' => 'datetime',
        'registration_start' => 'datetime',
        'registration_end' => 'datetime',
    ];

    public function registrations()
    {
        return $this->hasMany(Registration::class, 'activity_id');
    }

    public function students()
    {
        return $this->belongsToMany(Student::class, 'registrations', 'activity_id', 'student_id')
                    ->withPivot('check', 'created_at', 'updated_at');
    }
}

