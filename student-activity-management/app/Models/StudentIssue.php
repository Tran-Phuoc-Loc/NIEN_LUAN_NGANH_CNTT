<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentIssue extends Model
{
    use HasFactory;
    protected $fillable = ['student_id', 'student_name', 'message', 'is_resolved'];
}
