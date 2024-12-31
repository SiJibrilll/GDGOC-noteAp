<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Note extends Model
{

    protected $primaryKey = 'note_id';

    protected $fillable = [
        'title',
        'content',
        'tags',
        'folder',
        'is_pinned'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
