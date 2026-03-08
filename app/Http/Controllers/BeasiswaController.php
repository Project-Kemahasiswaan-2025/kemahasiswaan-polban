<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class BeasiswaController extends Controller
{
    public function index(): View
    {
        return view('pages.beasiswa.index');
    }

    public function show($id): View
    {
        $apiUrl = config('beasiswa.api_url');
        $useMock = config('app.debug');
        $beasiswa = null;

        if ($apiUrl && !$useMock) {
            try {
                $response = \Illuminate\Support\Facades\Http::get($apiUrl . '/' . $id);
                if ($response->successful()) {
                    $beasiswa = $response->json()['data'] ?? null;
                }
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error("Beasiswa Detail Web exception: " . $e->getMessage());
            }
        }

        // Fallback to mock data
        if (!$beasiswa && $useMock) {
            $mockDataPath = 'mock_beasiswa_detail.json';
            if (\Illuminate\Support\Facades\Storage::disk('public')->exists($mockDataPath)) {
                $data = json_decode(\Illuminate\Support\Facades\Storage::disk('public')->get($mockDataPath), true);
                $beasiswa = $data['data'] ?? null;
            }
        }

        if (!$beasiswa) {
            abort(404, 'Beasiswa tidak ditemukan');
        }

        $data['beasiswa'] = $beasiswa;

        return view('pages.beasiswa.detail', $data);
    }
}
