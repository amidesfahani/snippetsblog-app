<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'snippet_id',
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
