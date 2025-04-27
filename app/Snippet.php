<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Snippet extends Model
{
    const UPDATED_AT = null;

    protected $fillable = [
        'user_id',
        'title',
        'code',
        'language',
        'created_at',
    ];

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            $model->language = strtolower($model->language);
        });
    }

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
}
