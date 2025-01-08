<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Note;

class SharedNote extends Model
{
    protected $primaryKey = 'share_id';

    protected $fillable = [
        'note_id',
        'shared_by_user_id',
        'shared_with_id',
        'permission',
        'shared_at'
    ];


    public function shared_by() {
        return $this->belongsTo(User::class, 'shared_by_user_id');
    }

    public function shared_with() {
        return $this->belongsTo(User::class, 'shared_with_id');
    }

    public function note() {
        return $this->belongsTo(Note::class, 'note_id');
    }
}
