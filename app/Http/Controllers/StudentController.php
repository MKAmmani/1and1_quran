<?php

namespace App\Http\Controllers;

use App\Enums\UserRole;
use App\Models\LiveSession;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Announcement;

class StudentController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display the student dashboard.
     */
    public function dashboard()
    {
        // Get all live sessions. The `join` policy will determine if a student can actually join.
        $liveSessions = LiveSession::where('status', 'live')
            ->with('teacher')
            ->get();

        $announcements = Announcement::join('announcement_recipients', 'announcements.id', '=', 'announcement_recipients.announcement_id')
            ->where('announcement_recipients.student_id', auth()->id())
            ->latest('announcements.created_at')
            ->get();

        // Recent Activities for the student
        $recentActivities = LiveSession::whereHas('participants', function ($query) {
            $query->where('user_id', auth()->id());
        })
        ->where('status', 'ended')
        ->latest()
        ->with('teacher') // Eager load teacher for display
        ->take(3)
        ->get();

        // Upcoming Classes for the student
        $upcomingClasses = LiveSession::whereHas('participants', function ($query) {
            $query->where('user_id', auth()->id());
        })
        ->where('status', 'scheduled')
        ->orderBy('started_at')
        ->with('teacher') // Eager load teacher for display
        ->get();

        return Inertia::render('Student/Index', [
            'liveSessions' => $liveSessions,
            'announcements' => $announcements,
            'recentActivities' => $recentActivities,
            'upcomingClasses' => $upcomingClasses,
        ]);
    }

    /**
     * Request to join a live session.
     */
    public function requestToJoin(LiveSession $liveSession)
    {
        $student = auth()->user();

        // Create a join request
        $joinRequest = \App\Models\JoinRequest::firstOrCreate(
            [
                'live_session_id' => $liveSession->id,
                'student_id' => $student->id,
            ],
            ['status' => 'pending']
        );

        // Broadcast an event to the teacher
        event(new \App\Events\JoinRequestSent($joinRequest));

        return redirect()->back()->with('success', 'Your request to join has been sent to the teacher.');
    }

    /**
     * Join a live session.
     */
    public function join(LiveSession $liveSession)
    {
        $this->authorize('join', $liveSession);

        return inertia('LiveSession/Join', [
            'liveSession' => $liveSession,
            'sessionName' => $liveSession->session_name,
            'userName' => auth()->user()->first_name . ' ' . auth()->user()->last_name,
        ]);
    }
}
