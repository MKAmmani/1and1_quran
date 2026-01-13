<?php

use App\Models\LiveSession;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Log;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('live-session.{sessionId}', function ($user, $sessionId) {
    // Check if the user is either the teacher or a participant in this session
    if (!$user) {
        Log::warning('Unauthorized access attempt to live-session channel', [
            'session_id' => $sessionId,
            'ip' => request()->ip(),
        ]);
        return false;
    }

    $liveSession = \App\Models\LiveSession::find($sessionId);

    if (!$liveSession) {
        Log::warning('Attempt to access non-existent live session', [
            'session_id' => $sessionId,
            'user_id' => $user->id,
        ]);
        return false;
    }

    // Allow if user is the teacher of the session
    if ((int) $liveSession->teacher_id === (int) $user->id) {
        return ['user_id' => $user->id, 'user_name' => $user->first_name . ' ' . $user->last_name];
    }

    // Allow if user is a participant in the session
    $isParticipant = $liveSession->participants()->where('user_id', $user->id)->exists();

    if ($isParticipant) {
        return ['user_id' => $user->id, 'user_name' => $user->first_name . ' ' . $user->last_name];
    }

    Log::warning('Unauthorized user attempted to access live session channel', [
        'session_id' => $sessionId,
        'user_id' => $user->id,
        'user_role' => $user->role ?? 'unknown',
    ]);

    return false;
});

Broadcast::channel('teacher.{teacherId}', function ($user, $teacherId) {
    return (int) $user->id === (int) $teacherId;
});

Broadcast::channel('student.{studentId}', function ($user, $studentId) {
    return (int) $user->id === (int) $studentId;
});
