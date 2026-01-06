<?php

namespace App\Http\Controllers;

use App\Services\QuranApiService;
use Inertia\Inertia;

class QuranController extends Controller
{
    public function surahs(QuranApiService $quran)
    {
        return Inertia::render('Quran/Surahs', [
            'surahs' => $quran->getChapters(),
        ]);
    }
    public function showSurah(QuranApiService $quran, int $surah, int $page = 1)
    {
        $chapters = $quran->getChapters();
        $currentSurah = collect($chapters['chapters'])->firstWhere('id', $surah);
        $perPage = 4;
        $startAyah = ($page - 1) * $perPage + 1;
        return Inertia::render('Quran/Page', [
            'surah_details' => $currentSurah,
            'verses' => $quran->getMultipleAyahs($surah, $startAyah, $perPage),
            'currentPage' => (int)$page,
            'totalPages' => ceil($currentSurah['verses_count'] / $perPage),
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
