<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class BeasiswaController extends Controller
{
    public function index(Request $request)
    {
        $apiUrl = config('beasiswa.api_url');
        $useMock = config('app.debug');

        if ($apiUrl && !$useMock) {
            try {
                $response = Http::get($apiUrl, $request->all());
                if ($response->successful()) {
                    return $response->json();
                }
                Log::error("Beasiswa API error: " . $response->status());
            } catch (\Exception $e) {
                Log::error("Beasiswa API exception: " . $e->getMessage());
            }
        }

        // Fallback to mock data (only in debug/development mode)
        $mockDataPath = 'mock_beasiswa.json';
        if ($useMock && Storage::disk('public')->exists($mockDataPath)) {
            $data = json_decode(Storage::disk('public')->get($mockDataPath), true);

            // Basic filtering for mock data
            if (isset($data['data']['data'])) {
                $items = collect($data['data']['data']);

                if ($request->has('search')) {
                    $search = strtolower($request->search);
                    $items = $items->filter(function ($item) use ($search) {
                        return str_contains(strtolower($item['nama_beasiswa']), $search) ||
                            str_contains(strtolower($item['deskripsi']), $search);
                    });
                }

                if ($request->has('tipe_beasiswa') && $request->tipe_beasiswa != '') {
                    $items = $items->where('tipe_beasiswa', $request->tipe_beasiswa);
                }

                if ($request->has('jenis_beasiswa') && $request->jenis_beasiswa != '') {
                    $items = $items->where('jenis_beasiswa', $request->jenis_beasiswa);
                }

                if ($request->has('status') && $request->status != '') {
                    $items = $items->where('status_beasiswa', $request->status);
                }

                $data['data']['data'] = $items->values()->all();
                $data['data']['total'] = count($data['data']['data']);
            }

            $data['base_url'] = config('beasiswa.base_url');
            return response()->json($data);
        }

        return response()->json([
            'success' => false,
            'message' => 'Gagal mengambil data beasiswa',
            'data' => [
                'data' => [],
                'total' => 0
            ]
        ]);
    }

    public function show($id)
    {
        $apiUrl = config('beasiswa.api_url');
        $useMock = config('app.debug');

        if ($apiUrl && !$useMock) {
            try {
                $response = Http::get($apiUrl . '/' . $id);
                if ($response->successful()) {
                    return $response->json();
                }
                if ($response->status() == 404) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Beasiswa tidak ditemukan',
                        'data' => null
                    ], 404);
                }
                Log::error("Beasiswa Detail API error: " . $response->status());
            } catch (\Exception $e) {
                Log::error("Beasiswa Detail API exception: " . $e->getMessage());
            }
        }

        // Fallback to mock data
        $mockDataPath = 'mock_beasiswa_detail.json';
        if ($useMock && Storage::disk('public')->exists($mockDataPath)) {
            $data = json_decode(Storage::disk('public')->get($mockDataPath), true);
            return response()->json($data);
        }

        return response()->json([
            'success' => false,
            'message' => 'Gagal mengambil detail beasiswa',
            'data' => null
        ], 500);
    }

    public function penerima($id)
    {
        $apiUrl = config('beasiswa.api_url');
        $useMock = config('app.debug');

        if ($apiUrl && !$useMock) {
            try {
                $response = Http::get($apiUrl . '/' . $id . '/penerima');
                if ($response->successful()) {
                    return $response->json();
                }
                Log::error("Beasiswa Penerima API error: " . $response->status());
            } catch (\Exception $e) {
                Log::error("Beasiswa Penerima API exception: " . $e->getMessage());
            }
        }

        // Fallback to mock data
        $mockDataPath = 'mock_beasiswa_penerima.json';
        if ($useMock && Storage::disk('public')->exists($mockDataPath)) {
            $data = json_decode(Storage::disk('public')->get($mockDataPath), true);
            return response()->json($data);
        }

        return response()->json([
            'success' => false,
            'message' => 'Gagal mengambil data penerima beasiswa',
            'data' => null
        ], 500);
    }
}
