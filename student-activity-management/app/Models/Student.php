<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'user_id',
        'name',
        'email',
        'phone',
        'joining_date',
        'card_issuing_place',
        'avatar'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id'); // Liên kết đến bảng users qua user_id
    }

    public function registrations()
    {
        return $this->hasMany(Registration::class, 'user_id', 'id');
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class, 'user_id', 'user_id'); // user_id trong students liên kết với user_id trong notifications
    }
    // protected $dates = ['joining_date'];

    // public function setJoiningDateAttribute($value)
    // {
    //     $this->attributes['joining_date'] = Carbon::createFromFormat('d/m/Y', $value)->format('Y-m-d');
    // }

    // Quan hệ nhiều-nhiều thông qua bảng 'registrations'
    public function activities()
    {
        return $this->belongsToMany(Activity::class, 'unregistered_attendances', 'student_id', 'activity_id')
                    ->withPivot('check', 'created_at', 'updated_at')
                    ->wherePivot('check', 1); // Lọc chỉ những hoạt động đã tham gia
    }
}
