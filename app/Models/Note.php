<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\File;

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

    public function shared_with() {
        return $this->belongsToMany(User::class, 'shared_notes', 'note_id', 'shared_with_id')->withPivot('share_id', 'shared_at', 'permission');
    }

    public function files() {
        return $this->hasMany(File::class, 'note_id');
    }
}
