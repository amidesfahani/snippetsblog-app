<?php

namespace App;

use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    const UPDATED_AT = null;

    protected $fillable = [
        'snippet_id',
        'user_id',
        'comment',
        'created_at',
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::saved(function ($model) {
            Cache::tags(['snippets'])->flush();
        });
    
        static::deleted(function ($model) {
            Cache::tags(['snippets'])->flush();
        });
    }

    public function snippet()
    {
        return $this->belongsTo(Snippet::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
