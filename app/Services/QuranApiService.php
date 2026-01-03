<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class QuranApiService
{
    protected string $baseUrl = 'https://api.quran.com/api/v4';

    public function getSurah(int $surahNumber)
    {
        return Cache::remember(
            "quran_surah_{$surahNumber}",
            now()->addDay(),
            function () use ($surahNumber) {
                return Http::get("{$this->baseUrl}/verses/by_chapter/{$surahNumber}", [
                    'language' => 'en',
                    'words' => false,
                    'translations' => 131, // Sahih International
                ])->json();
            }
        );
    }

    public function getAyah(int $surah, int $ayah)
    {
        return Cache::remember(
            "quran_ayah_{$surah}_{$ayah}",
            now()->addDay(),
            function () use ($surah, $ayah) {
                return Http::get("{$this->baseUrl}/verses/by_key/{$surah}:{$ayah}", [
                    'language' => 'en',
                    'translations' => 131,
                ])->json();
            }
        );
    }
}
