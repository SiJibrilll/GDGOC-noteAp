<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Note;
use App\Models\SharedNote;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    function notes() {
        return $this->hasMany(Note::class);
    }

    public function shared_by() {
        return $this->belongsToMany(Note::class, 'shared_notes', 'shared_by_user_id', 'note_id')->withPivot('shared_at');
    }

    public function shared_with() {
        return $this->belongsToMany(Note::class, 'shared_notes', 'shared_with_id', 'note_id')->withPivot('shared_at');
    }

    public function revoke_sharing($shared_with_id, $note_id) {
        SharedNote::where('shared_by_user_id', $this->id)->where('shared_with_id', $shared_with_id)->where('note_id', $note_id)->delete();

    }
}
