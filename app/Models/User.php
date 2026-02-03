<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enums\UserRole;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'role',
        'google_id',
        'google_token',
        'avatar',
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
            'role' => UserRole::class,
        ];
    }

    /**
     * Get the user's full name.
     */
    public function getNameAttribute(): string
    {
        return trim($this->first_name . ' ' . $this->last_name);
    }

    public function liveSessions()
    {
        return $this->belongsToMany(LiveSession::class, 'session_participants', 'user_id', 'live_session_id')
                    ->withPivot('joined_at', 'left_at');
    }

    public function studentProgress()
    {
        return $this->hasMany(StudentProgress::class, 'student_id');
    }

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['progress', 'surahs_completed', 'current_surah_id', 'online'];

    /**
     * Get the student's online status.
     */
    public function getOnlineAttribute()
    {
        $last_activity = \DB::table('sessions')->where('user_id', $this->id)->max('last_activity');
        return $last_activity ? (time() - $last_activity) < 300 : false;
    }

    /**
     * Get the student's latest progress record.
     */
    public function getLatestProgressAttribute()
    {
        return $this->studentProgress()->latest()->first();
    }

    /**
     * Get the student's overall progress percentage.
     */
    public function getProgressAttribute()
    {
        $latestProgress = $this->getLatestProgressAttribute();
        if ($latestProgress && $latestProgress->surah_id) {
            return floor((($latestProgress->surah_id - 1) / 114) * 100);
        }
        return 0;
    }

    /**
     * Get the number of surahs completed by the student.
     */
    public function getSurahsCompletedAttribute()
    {
        $latestProgress = $this->getLatestProgressAttribute();
        if ($latestProgress && $latestProgress->surah_id) {
            return $latestProgress->surah_id - 1;
        }
        return 0;
    }

    /**
     * Get the surah the student is currently on.
     */
    public function getCurrentSurahIdAttribute()
    {
        $latestProgress = $this->getLatestProgressAttribute();
        if ($latestProgress) {
            return $latestProgress->surah_id;
        }
        return null;
    }
}
