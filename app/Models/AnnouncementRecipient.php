<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AnnouncementRecipient extends Model
{
    protected $fillable = ['announcement_id', 'student_id', 'read_at'];
}
