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

        if ($apiUrl) {
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

        // Fallback to mock data
        $mockDataPath = 'mock_beasiswa.json';
        if (Storage::disk('public')->exists($mockDataPath)) {
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

                // Jurusan filter (if applicable in mock data, currently not direct field but based on request)
                // Assuming "jurusan" might be a future field or partially handled.

                $data['data']['data'] = $items->values()->all();
                $data['data']['total'] = count($data['data']['data']);
            }

            $data['base_url'] = config('beasiswa.base_url');
            return response()->json($data);
        }

        return response()->json([
            'success' => false,
            'message' => 'No data found',
            'data' => null
        ], 404);
    }
}
