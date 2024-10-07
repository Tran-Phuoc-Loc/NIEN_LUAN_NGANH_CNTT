<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentClass extends Model
{
    use HasFactory;
    
    protected $table = 'student_classes'; // Đổi tên bảng nếu cần

    // Các thuộc tính có thể được gán hàng loạt
    protected $fillable = [
        'name',
        'department_id', // Nếu lớp thuộc về một khoa
    ];

    // Quan hệ với sinh viên
    public function students()
    {
        return $this->hasMany(Student::class);
    }

    // // Quan hệ với khoa
    // public function department()
    // {
    //     return $this->belongsTo(Department::class);
    // }
}
