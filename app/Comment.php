<?php

namespace App;

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

    public function snippet()
    {
        return $this->belongsTo(Snippet::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
