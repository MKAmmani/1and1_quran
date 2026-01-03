<?php

use App\Models\LiveSession;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('live-session.{sessionId}', function ($user, $sessionId) {
    // Check if the user is authorized to access this live session
    // This could be through being the teacher or being a participant
    $liveSession = \App\Models\LiveSession::find($sessionId);

    if (!$liveSession) {
        return false;
    }

    // Allow the teacher of the session
    if ($liveSession->teacher_id == $user->id) {
        return true;
    }

    // Allow students who are participants in the session
    $isParticipant = $liveSession->participants()
        ->where('user_id', $user->id)
        ->exists();

    return $isParticipant;
});

Broadcast::channel('teacher.{teacherId}', function ($user, $teacherId) {
    return (int) $user->id === (int) $teacherId;
});

Broadcast::channel('student.{studentId}', function ($user, $studentId) {
    return (int) $user->id === (int) $studentId;
});
