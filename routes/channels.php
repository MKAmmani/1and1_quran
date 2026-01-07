<?php

use App\Models\LiveSession;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('live-session.{sessionId}', function ($user, $sessionId) {
    // Temporarily allow all for testing
    return true;
});

Broadcast::channel('teacher.{teacherId}', function ($user, $teacherId) {
    return (int) $user->id === (int) $teacherId;
});

Broadcast::channel('student.{studentId}', function ($user, $studentId) {
    return (int) $user->id === (int) $studentId;
});

if (config('broadcasting.default')) {
    Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
        return (int) $user->id === (int) $id;
    });
}

