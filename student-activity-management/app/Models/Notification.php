<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'student_id', 'message', 'is_admin', 'send_to_all', 'send_to_group']; // Các trường có thể gán

    public function student()
    {
        return $this->belongsTo(Student::class, 'user_id', 'user_id');
    }
    
    public function user()
    {
        return $this->belongsTo(Student::class, 'user_id');
    }
}
