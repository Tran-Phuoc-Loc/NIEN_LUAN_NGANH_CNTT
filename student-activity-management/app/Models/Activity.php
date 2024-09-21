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
    ];

    // Ép kiểu các thuộc tính thành datetime
    protected $casts = [
        'date' => 'datetime',
        'registration_start' => 'datetime',
        'registration_end' => 'datetime',
    ];

    public function registrations()
    {
        return $this->hasMany(Registration::class);
    }
}

