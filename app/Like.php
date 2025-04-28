<?php

namespace App;

use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'snippet_id',
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
