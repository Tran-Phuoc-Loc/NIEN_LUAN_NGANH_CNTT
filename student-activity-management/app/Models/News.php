<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    use HasFactory;
    protected $fillable = ['title', 'content', 'image']; // Thêm cột 'image' nếu cần

    // Quan hệ với NewsImage
    public function images()
    {
        return $this->hasMany(NewsImage::class);
    }
}
