<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'photo_id',
        'reason',
        'custom_reason',
        'status',
        'admin_comment',
        'complaint_count',
    ];

    const STATUSES = [
        'Новая',
        'В процессе',
        'Решена',
        'Пользователь заблокирован',
        'Контент удален',
        'Нарушений не замечено закрыто',
        'Закрыт применены правила в отношения пользователя',
        'Ответ от администрации',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function photo()
    {
        return $this->belongsTo(Photo::class);
    }

    public function userReports()
    {
        return $this->hasMany(Report::class, 'photo_id', 'photo_id');
    }
}
