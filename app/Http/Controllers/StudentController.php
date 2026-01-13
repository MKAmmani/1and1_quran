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

        return redirect()->route('student.waiting-approval', [
            'liveSession' => $liveSession,
            'joinRequest' => $joinRequest
        ]);
    }

    /**
     * Join a live session.
     */
    public function join(LiveSession $liveSession)
    {
        // Debug logging
        \Log::info('Student attempting to join session', [
            'user_id' => auth()->id(),
            'session_id' => $liveSession->id,
            'session_status' => $liveSession->status,
            'user_role' => auth()->user()->role ?? 'unknown',
            'is_participant' => $liveSession->participants()->where('user_id', auth()->id())->exists()
        ]);

        $this->authorize('join', $liveSession);

        // Update joined_at if not set
        \App\Models\SessionParticipant::where('live_session_id', $liveSession->id)
            ->where('user_id', auth()->id())
            ->whereNull('joined_at')
            ->update(['joined_at' => now()]);

        \Log::info('Authorization passed, returning Join page', [
            'user_id' => auth()->id(),
            'session_id' => $liveSession->id
        ]);

        return inertia('LiveSession/Join', [
            'liveSession' => $liveSession,
            'sessionName' => $liveSession->session_name,
            'userName' => auth()->user()->first_name . ' ' . auth()->user()->last_name,
        ]);
    }

    /**
     * Show waiting for approval page.
     */
    public function waitingForApproval(LiveSession $liveSession, \App\Models\JoinRequest $joinRequest = null)
    {
        // Find the join request for this student and session if not provided
        if (!$joinRequest) {
            $joinRequest = \App\Models\JoinRequest::where('live_session_id', $liveSession->id)
                ->where('student_id', auth()->id())
                ->firstOrFail();
        }

        // Debug logging
        \Log::info('Displaying waiting for approval page', [
            'user_id' => auth()->id(),
            'session_id' => $liveSession->id,
            'join_request_id' => $joinRequest->id,
            'join_request_status' => $joinRequest->status
        ]);

        return Inertia::render('Student/WaitingForApproval', [
            'liveSession' => $liveSession,
            'joinRequest' => $joinRequest,
        ]);
    }

    /**
     * Check if join request has been approved.
     */
    public function checkJoinRequestStatus(\App\Models\JoinRequest $joinRequest)
    {
        // Ensure the authenticated user owns this join request
        if ($joinRequest->student_id !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Refresh the join request from the database
        $joinRequest->refresh();

        return response()->json([
            'status' => $joinRequest->status,
            'approved' => $joinRequest->status === 'approved',
            'live_session_id' => $joinRequest->live_session_id
        ]);
    }
}
