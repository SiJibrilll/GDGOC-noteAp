<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Note;

class File extends Model
{
    protected $fillable = [
        'path',
        'note_id'
    ];

    public function note() {
        return $this->belongsTo(Note::class, 'note_id');
    }
}
