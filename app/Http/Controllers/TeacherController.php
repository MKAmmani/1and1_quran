<?php

namespace App\Http\Controllers;

use App\Models\LiveSession;
use Illuminate\Http\Request;

class TeacherController extends Controller
{
    public function prepare_class(){
        return inertia('Teacher/Prepare_class');
    }

    public function announcement(){
        return inertia('Teacher/Announcement');
    }

    public function start(Request $request)
    {
        // Find an existing live session for the teacher or create a new one
        $liveSession = LiveSession::firstOrCreate(
            ['teacher_id' => auth()->id(), 'status' => 'live'],
            [
                'title' => $request->input('title', 'Quran Class Session'),
                'session_name' => 'session-' . time(),
                'started_at' => now(),
            ]
        );

        return response()->json([
            'success' => true,
            'liveSessionId' => $liveSession->id,
            'sessionName' => $liveSession->session_name,
            'message' => 'Live session started successfully.'
        ]);
    }

    public function live(Request $request){
        // The 'live' method is now only responsible for returning the view.
        // Session creation will be handled by a dedicated 'start' method.
        // We can pass any necessary data to the view, like an existing session if applicable.
        $liveSession = LiveSession::where('teacher_id', auth()->id())
                                  ->where('status', 'live')
                                  ->first();

        return inertia('Teacher/Live', [
            'liveSessionId' => $liveSession ? $liveSession->id : null,
            'sessionName' => $liveSession ? $liveSession->session_name : null,
            'userName' => auth()->user()->first_name . ' ' . auth()->user()->last_name,
            'liveSession' => $liveSession ? $liveSession->toArray() : null,
        ]);
    }

    public function end(LiveSession $liveSession)
    {
        // Ensure the authenticated user is the teacher of this session
        if ($liveSession->teacher_id !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $liveSession->update([
            'status' => 'ended',
            'ended_at' => now(),
        ]);

        return response()->json(['success' => true, 'message' => 'Session ended successfully.']);
    }

    public function getJoinRequests(LiveSession $liveSession)
    {
        // Ensure the authenticated user is the teacher of this session
        if ($liveSession->teacher_id !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $joinRequests = $liveSession->joinRequests()->where('status', 'pending')->with('student')->get();

        return response()->json($joinRequests);
    }

    public function approveJoinRequest(\App\Models\JoinRequest $joinRequest)
    {
        // Ensure the authenticated user is the teacher of this session
        if ($joinRequest->liveSession->teacher_id !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $joinRequest->update(['status' => 'approved']);

        // Add student to participants
        \App\Models\SessionParticipant::create([
            'live_session_id' => $joinRequest->live_session_id,
            'user_id' => $joinRequest->student_id,
            'joined_at' => now(),
        ]);

        event(new \App\Events\JoinRequestApproved($joinRequest));

        return response()->json(['success' => true]);
    }

    public function declineJoinRequest(\App\Models\JoinRequest $joinRequest)
    {
        // Ensure the authenticated user is the teacher of this session
        if ($joinRequest->liveSession->teacher_id !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $joinRequest->update(['status' => 'declined']);

        return response()->json(['success' => true]);
    }

    public function getLiveSessions(){
        return LiveSession::where('status', 'live')->with('teacher')->get();
    }
}
