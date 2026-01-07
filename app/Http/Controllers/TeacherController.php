<?php

namespace App\Http\Controllers;

use App\Events\QuranVerseChanged;
use App\Models\LiveSession;
use App\Models\SessionParticipant;
use Illuminate\Http\Request;
use App\Models\User;
use App\Enums\UserRole;
use App\Services\QuranApiService;
use App\Models\Announcement;
use App\Models\AnnouncementRecipient;

class TeacherController extends Controller
{
    public function index()
    {
        $teacher = auth()->user();

        // Get all unique students who have participated in the teacher's sessions.
        $studentIds = SessionParticipant::whereIn('live_session_id', function ($query) use ($teacher) {
            $query->select('id')->from('live_sessions')->where('teacher_id', $teacher->id);
        })->distinct()->pluck('user_id');

        $students = User::whereIn('id', $studentIds)->get()->map(function ($student) {
            $last_activity = \DB::table('sessions')->where('user_id', $student->id)->max('last_activity');
            $student->online = $last_activity ? (time() - $last_activity) < 300 : false;
            // Mocking progress
            $student->progress = rand(10, 90);
            $student->surahs_completed = floor(114 * ($student->progress / 100));
            return $student;
        });

        // Quick Stats
        $totalStudents = $students->count();
        $classesThisWeek = LiveSession::where('teacher_id', $teacher->id)
            ->where('created_at', '>=', now()->subWeek())
            ->count();

        // Recent Activities (last 3 ended sessions)
        $recentActivities = LiveSession::where('teacher_id', $teacher->id)
            ->where('status', 'ended')
            ->latest()
            ->take(3)
            ->get();

        // Upcoming Classes (scheduled sessions)
        $upcomingClasses = LiveSession::where('teacher_id', $teacher->id)
            ->where('status', 'scheduled')
            ->orderBy('created_at')
            ->get();

        return inertia('Teacher/Index', [
            'students' => $students,
            'totalStudents' => $totalStudents,
            'classesThisWeek' => $classesThisWeek,
            'recentActivities' => $recentActivities,
            'upcomingClasses' => $upcomingClasses,
        ]);
    }

    public function prepare_class(QuranApiService $quran)
    {
        $teacher = auth()->user();
        $studentIds = SessionParticipant::whereIn('live_session_id', function ($query) use ($teacher) {
            $query->select('id')->from('live_sessions')->where('teacher_id', $teacher->id);
        })->distinct()->pluck('user_id');
        $students = User::whereIn('id', $studentIds)->get();
        $surahs = $quran->getChapters();

        return inertia('Teacher/Prepare_class', [
            'students' => $students,
            'surahs' => $surahs['chapters'],
        ]);
    }

    public function announcement()
    {
        $teacher = auth()->user();
        $studentIds = SessionParticipant::whereIn('live_session_id', function ($query) use ($teacher) {
            $query->select('id')->from('live_sessions')->where('teacher_id', $teacher->id);
        })->distinct()->pluck('user_id');
        $students = User::whereIn('id', $studentIds)->get();

        return inertia('Teacher/Announcement', [
            'students' => $students,
        ]);
    }

    public function students()
    {
        $teacher = auth()->user();

        // Get all unique students who have participated in the teacher's sessions.
        $studentIds = SessionParticipant::whereIn('live_session_id', function ($query) use ($teacher) {
            $query->select('id')->from('live_sessions')->where('teacher_id', $teacher->id);
        })->distinct()->pluck('user_id');

        $students = User::whereIn('id', $studentIds)->get()->map(function ($student) {
            $last_activity = \DB::table('sessions')->where('user_id', $student->id)->max('last_activity');
            $student->online = $last_activity ? (time() - $last_activity) < 300 : false;
            // Mocking progress
            $student->progress = rand(10, 90);
            $student->surahs_completed = floor(114 * ($student->progress / 100));
            return $student;
        });

        return inertia('Teacher/Student', [
            'students' => $students,
        ]);
    }

    public function schedule(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'students' => 'required|array',
            'date' => 'required|date',
            'time' => 'required',
        ]);

        $started_at = $request->date . ' ' . $request->time;

        $liveSession = LiveSession::create([
            'teacher_id' => auth()->id(),
            'title' => $request->title,
            'session_name' => 'session-' . time(),
            'status' => 'scheduled',
            'started_at' => $started_at,
        ]);

        foreach ($request->students as $studentId) {
            SessionParticipant::create([
                'live_session_id' => $liveSession->id,
                'user_id' => $studentId,
            ]);
        }

        return redirect()->route('teacher.index')->with('success', 'Class scheduled successfully.');
    }
    
    public function sendAnnouncement(Request $request)
    {
        $request->validate([
            'message' => 'required|string',
            'students' => 'required|array',
        ]);

        $announcement = Announcement::create([
            'teacher_id' => auth()->id(),
            'message' => $request->message,
        ]);

        foreach ($request->students as $studentId) {
            AnnouncementRecipient::create([
                'announcement_id' => $announcement->id,
                'student_id' => $studentId,
            ]);
        }

        return redirect()->back()->with('success', 'Announcement sent successfully.');
    }

    public function start(Request $request)
    {
        $request->validate([
            'title' => 'nullable|string|max:255',
            'students' => 'required|array',
        ]);

        // Find an existing live session for the teacher or create a new one
        $liveSession = LiveSession::firstOrCreate(
            ['teacher_id' => auth()->id(), 'status' => 'live'],
            [
                'title' => $request->input('title', 'Quran Class Session'),
                'session_name' => 'session-' . time(),
                'started_at' => now(),
            ]
        );

        foreach ($request->students as $studentId) {
            SessionParticipant::create([
                'live_session_id' => $liveSession->id,
                'user_id' => $studentId,
            ]);
        }

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


    public function history()
    {
        $sessions = LiveSession::where('status', 'ended')
            ->with('teacher', 'students')
            ->orderBy('id', 'desc')
            ->get();

        return inertia('Teacher/History', [
            'sessions' => $sessions,
        ]);
    }

    public function getLiveSessions(){
        return LiveSession::where('status', 'live')->with('teacher')->get();
    }

    public function changeQuranVerse(Request $request, LiveSession $liveSession)
    {
        if ($liveSession->teacher_id !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'surah' => 'required|integer|min:1|max:114',
            'ayah' => 'required|integer|min:1',
            'surah_name' => 'required|string',
        ]);

        broadcast(new QuranVerseChanged($liveSession->id, $validated['surah'], $validated['ayah'], $validated['surah_name']));

        return response()->json(['success' => true]);
    }
}