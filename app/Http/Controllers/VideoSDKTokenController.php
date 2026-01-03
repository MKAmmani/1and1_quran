<?php

namespace App\Http\Controllers;

use App\Services\ZoomVideoSDKService;
use Illuminate\Http\Request;

class VideoSDKTokenController extends Controller
{
    public function generate(Request $request)
    {
        $request->validate([
            'session_name' => 'required|string',
            'role' => 'required|integer',
        ]);

        return response()->json([
            'token' => ZoomVideoSDKService::generateToken(
                $request->session_name,
                auth()->id(),
                $request->role
            )
        ]);
    }
}
