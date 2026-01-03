<?php

use App\Http\Controllers\ChatMessageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StudentController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Enums\UserRole;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\VideoSDKTokenController;
use App\Http\Controllers\QuranController;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
})->name('welcome');

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/teacher/dashboard', function () {
    return Inertia::render('Teacher/Index');
})->middleware(['auth', 'verified', 'role:' . UserRole::Teacher->value])->name('teacher.index');

Route::post('/video-sdk/token', [VideoSDKTokenController::class, 'generate'])
    ->middleware('auth');

Route::middleware(['auth'])->group(function () {
    Route::post('/broadcasting/auth', function () {
        return auth()->user();
    });

    Route::get('/live-sessions/{liveSession}/chat', [ChatMessageController::class, 'index']);
    Route::post('/chat-messages', [ChatMessageController::class, 'store']);
    Route::post('/live-sessions/{liveSession}/end', [TeacherController::class, 'end'])->name('live-sessions.end');
    Route::get('/live-sessions/{liveSession}/join', [StudentController::class, 'join'])
        ->middleware('role:' . \App\Enums\UserRole::Student->value)
        ->name('live-session.join');
    Route::post('/live-sessions/{liveSession}/request-join', [StudentController::class, 'requestToJoin'])
        ->middleware('role:' . \App\Enums\UserRole::Student->value)
        ->name('live-session.request-join');
});

Route::get('/student/dashboard', [StudentController::class, 'dashboard'])
    ->middleware(['auth', 'verified', 'role:' . UserRole::Student->value])
    ->name('student.dashboard');

Route::middleware(['auth', 'verified', 'role:' . UserRole::Teacher->value])->group(function(){
    Route::get('/prepare-student',[TeacherController::class, 'prepare_class'])->name('prepre_class');
    Route::get('/Announcement',[TeacherController::class, 'announcement'])->name('announcement');
    Route::get('/live-class', [TeacherController::class,'live'])->name('live');
    Route::post('/live-class/start', [TeacherController::class, 'start'])->name('live-class.start');
    Route::get('/live-sessions/{liveSession}/join-requests', [TeacherController::class, 'getJoinRequests'])->name('live-sessions.join-requests');
    Route::post('/join-requests/{joinRequest}/approve', [TeacherController::class, 'approveJoinRequest'])->name('join-requests.approve');
    Route::post('/join-requests/{joinRequest}/decline', [TeacherController::class, 'declineJoinRequest'])->name('join-requests.decline');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/quran/surah/{surah}', [QuranController::class, 'showSurah']);
    Route::get('/quran/ayah/{surah}/{ayah}', [QuranController::class, 'getAyah']);
});

require __DIR__ . '/auth.php';
