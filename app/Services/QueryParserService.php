<?php

namespace App\Services;

use App\Services\SynonymExpander;

class QueryParserService
{
    public function parse(string $query): array
    {
        $text = strtolower($query);

        $cleanText = $this->cleanText($text);

        $expander = new SynonymExpander();
        $expandedText = $expander->expand($cleanText);

        return [
            'jenis' => $this->parseJenis($text),
            'harga_min' => $this->parseHargaMin($text),
            'harga_max' => $this->parseHargaMax($text),
            'area' => $this->parseArea($text),
            'nlp_text' => $expandedText,
        ];
    }

    private function parseJenis(string $text): ?string
    {
        // eksplisit campur
        if (preg_match('/\b(campur|umum|bebas gender)\b/', $text)) {
            return 'campur';
        }

        // khusus putri
        if (preg_match('/\b(putri|cewek|perempuan)\b/', $text)) {
            return 'putri';
        }

        // khusus putra
        if (preg_match('/\b(putra|cowok|laki)\b/', $text)) {
            return 'putra';
        }

        // tidak disebut → jangan filter
        return null;
    }

    private function parseHargaMax(string $text): ?int
    {
        if (str_contains($text, 'murah')) {
            return 1000000;
        }

        if (preg_match('/(di bawah|kurang dari|<)\s*(\d+)\s*(jt|juta)?/', $text, $m)) {
            return ((int) $m[2]) * (isset($m[3]) ? 1000000 : 1);
        }

        return null;
    }

    private function parseHargaMin(string $text): ?int
    {
        if (preg_match('/(di atas|lebih dari|>)\s*(\d+)\s*(jt|juta)?/', $text, $m)) {
            return ((int) $m[2]) * (isset($m[3]) ? 1000000 : 1);
        }

        return null;
    }

    private function parseArea(string $text): ?string
    {
        $areas = [
            'purwokerto utara',
            'purwokerto timur',
            'purwokerto barat',
            'purwokerto selatan',
        ];

        foreach ($areas as $area) {
            if (str_contains($text, $area)) {
                return ucwords($area);
            }
        }
        return null;
    }

    private function cleanText(string $text): string
    {
        $remove = [
            'kost',
            'kos',
            'putri',
            'putra',
            'cewek',
            'cowok',
            'murah',
            'mahal',
            'di bawah',
            'di atas',
            'purwokerto utara',
            'purwokerto timur',
            'purwokerto barat',
            'purwokerto selatan'
        ];

        return trim(str_replace($remove, '', $text));
    }
}
