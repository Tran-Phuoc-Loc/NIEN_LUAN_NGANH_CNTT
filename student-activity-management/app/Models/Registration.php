<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Registration extends Model
{
    use HasFactory;
    protected $fillable = [
        'student_id',
        'full_name',
        'email',
        'phone',
        'department',
        'batch',
    ];

    public function student()
{
    return $this->belongsTo(Student::class, 'student_id', 'student_id');
}

public function activity()
{
    return $this->belongsTo(Activity::class);
}

}
