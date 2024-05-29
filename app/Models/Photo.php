<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Mail;
use App\Mail\PhotoBlocked;

class Photo extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'path', 'description', 'is_blocked', 'block_description',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function dislikes()
    {
        return $this->hasMany(Dislike::class);
    }

    public function scopeWithCounts($query)
    {
        return $query->withCount(['comments', 'likes', 'dislikes']);
    }

    public function blockPhoto($blockDescription)
    {
        $this->is_blocked = true;
        $this->block_description = $blockDescription;
        $this->save();

        $this->notifyUser();
    }

    protected function notifyUser()
    {
        Mail::to($this->user->email)->send(new PhotoBlocked($this));
    }

    public function canBeViewedBy($user)
    {
        if ($this->is_blocked && $this->user_id != $user->id) {
            return false;
        }
        return true;
    }
}
