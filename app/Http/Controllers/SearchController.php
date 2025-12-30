<?php

namespace App\Http\Controllers;

use App\Models\Kost;
use App\Services\QueryParserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $q = Kost::query();

        /**
         * =========================
         * NLP + QUERY PARSER
         * =========================
         */
        if ($request->filled('q')) {
            $rawQuery = trim($request->q);

            // 1) Parse Bahasa Indonesia (rule-based)
            $parser = new QueryParserService();
            $parsed = $parser->parse($rawQuery);

            /**
             * 2) Call NLP Service (FastAPI)
             */
            $nlpIds = null;

            if (!empty($parsed['nlp_text'])) {
                try {
                    $response = Http::timeout(3)->post(
                        'http://127.0.0.1:8001/search',
                        [
                            'query' => $parsed['nlp_text'],
                            'top_k' => 5
                        ]
                    );

                    if ($response->ok()) {
                        $nlpIds = collect($response->json('results'))
                            ->pluck('kost_id')
                            ->toArray();
                    }
                } catch (\Throwable $e) {
                    // NLP down → fallback
                    $nlpIds = null;
                }
            }

            /**
             * 3) Apply NLP result or fallback LIKE
             */
            if (!empty($nlpIds)) {
                $q->whereIn('id', $nlpIds)
                    ->orderByRaw("FIELD(id, " . implode(',', $nlpIds) . ")");
            } else {
                // fallback LIKE (aman)
                $q->where(function ($w) use ($rawQuery) {
                    $w->where('nama', 'like', "%{$rawQuery}%")
                        ->orWhere('kecamatan', 'like', "%{$rawQuery}%")
                        ->orWhere('kota', 'like', "%{$rawQuery}%");
                });
            }

            /**
             * 4) Apply HARD filters (SQL)
             */
            if ($parsed['jenis']) {
                $q->where('jenis', $parsed['jenis']);
            }
            if ($parsed['harga_min']) {
                $q->where('harga_bulanan', '>=', $parsed['harga_min']);
            }
            if ($parsed['harga_max']) {
                $q->where('harga_bulanan', '<=', $parsed['harga_max']);
            }
            if ($parsed['area']) {
                $q->where('kecamatan', $parsed['area']);
            }
        }

        /**
         * =========================
         * FILTER MANUAL (UI)
         * =========================
         */
        if ($request->filled('area')) {
            $q->where('kecamatan', $request->area);
        }
        if ($request->filled('harga_min')) {
            $q->where('harga_bulanan', '>=', (int) $request->harga_min);
        }
        if ($request->filled('harga_max')) {
            $q->where('harga_bulanan', '<=', (int) $request->harga_max);
        }

        /**
         * =========================
         * SORTING
         * =========================
         */
        $sort = $request->get('sort', 'terbaru');
        match ($sort) {
            'harga_asc' => $q->orderBy('harga_bulanan', 'asc'),
            'harga_desc' => $q->orderBy('harga_bulanan', 'desc'),
            default => $q->latest(),
        };

        /**
         * =========================
         * PAGINATION & RESPONSE
         * =========================
         */
        $result = $q->paginate(12)->appends($request->query());

        return view('kost.search', [
            'kosts' => $result
        ]);
    }
}
