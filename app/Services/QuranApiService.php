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
            "quran_ayah_{$surah}_{$ayah}_v4",
            now()->addDay(),
            function () use ($surah, $ayah) {
                $response = Http::get("{$this->baseUrl}/verses/by_key/{$surah}:{$ayah}", [
                    'language' => 'en',
                    'words' => true,
                    'translations' => 131,
                    'fields' => 'text_uthmani,text_imlaei_simple,translations',
                ])->json();

                // If translations are not included, try to get them separately
                if (!isset($response['verse']['translations']) || empty($response['verse']['translations'])) {
                    $translationResponse = Http::get("{$this->baseUrl}/verses/by_key/{$surah}:{$ayah}/translations/131", [
                        'language' => 'en',
                    ])->json();

                    if (isset($translationResponse['translation'])) {
                        $response['verse']['translations'] = [$translationResponse['translation']];
                    }
                }

                return $response;
            }
        );
    }

    public function getMultipleAyahs(int $surah, int $startAyah, int $count = 4)
    {
        $verses = [];
        
        for ($i = 0; $i < $count; $i++) {
            $ayahNumber = $startAyah + $i;
            try {
                $response = $this->getAyah($surah, $ayahNumber);
                if (isset($response['verse'])) {
                    $verses[] = $response['verse'];
                }
            } catch (\Exception $e) {
                // Stop if we can't get more verses (end of surah)
                break;
            }
        }
        
        return ['verses' => $verses];
    }

    public function getChapters()
    {
        return Cache::remember(
            'quran_chapters',
            now()->addDay(),
            function () {
                return Http::get("{$this->baseUrl}/chapters", [
                    'language' => 'en',
                ])->json();
            }
        );
    }
}
