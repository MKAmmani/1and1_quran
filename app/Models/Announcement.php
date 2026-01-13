<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    protected $fillable = ['teacher_id', 'message'];

    public function recipients()
    {
        return $this->hasMany(AnnouncementRecipient::class);
    }
}
