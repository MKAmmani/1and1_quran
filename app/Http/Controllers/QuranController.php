<?php

namespace App\Http\Controllers;

use App\Services\QuranApiService;
use Inertia\Inertia;

class QuranController extends Controller
{
    public function surahs()
    {
        return Inertia::render('Quran/Surahs');
    }
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

    public function getMultipleAyahs(QuranApiService $quran, int $surah, int $startAyah, int $count = 4)
    {
        return response()->json(
            $quran->getMultipleAyahs($surah, $startAyah, $count)
        );
    }

    public function getChapters(QuranApiService $quran)
    {
        return response()->json(
            $quran->getChapters()
        );
    }
}
