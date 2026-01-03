<?php

namespace App\Http\Controllers;

use App\Services\QuranApiService;
use Inertia\Inertia;

class QuranController extends Controller
{
    public function showSurah(QuranApiService $quran, int $surah)
    {
        return Inertia::render('Quran/Surah', [
            'surah' => $quran->getSurah($surah),
        ]);
    }

    public function getAyah(QuranApiService $quran, int $surah, int $ayah)
    {
        return response()->json(
            $quran->getAyah($surah, $ayah)
        );
    }
}
