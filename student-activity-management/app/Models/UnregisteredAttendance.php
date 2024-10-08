<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UnregisteredAttendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'full_name',
        'email',
        'phone',
        'batch',
        'activity_id',
    ];
}
