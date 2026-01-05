<?php

namespace App\Services;

class SynonymExpander
{
    /**
     * Kamus sinonim domain kost
     */
    protected array $synonyms = [

        // kamar mandi
        'wc' => ['k. mandi'],
        'toilet' => ['k. mandi'],
        'km' => ['k. mandi'],
        'km dalam' => ['k. mandi dalam'],
        'wc dalam' => ['k. mandi dalam'],
        'km luar' => ['k. mandi luar'],
        'wc luar' => ['k. mandi luar'],
        'kamar mandi luar' => ['k. mandi luar'],
        'kamar mandi dalam' => ['k. mandi dalam'],
        'wc duduk' => ['kloset duduk'],
        'wc jongkok' => ['kloset jongkok'],
        'toilet duduk' => ['kloset duduk'],
        'toilet jongkok' => ['kloset jongkok'],

        // jenis
        'cewek' => ['putri'],
        'cowok' => ['putra'],
        'laki laki' => ['putra'],
        'perempuan' => ['putri'],

        // fasilitas
        'wifi' => ['internet'],
        'internet' => ['wifi'],
        'ac' => ['pendingin ruangan'],
        'kipas' => ['kipas angin'],
        'parkir motor' => ['parkiran'],
        'parkir mobil' => ['parkiran'],
        'luas' => ['ruang tamu'],
        'dapur' => ['dapur bersama'],


        // kebijakan
        'bebas' => ['jam malam bebas'],
        'bebas 24 jam' => ['jam malam bebas'],

        // utilitas
        'listrik gratis' => ['listrik termasuk'],
        'air gratis' => ['air termasuk'],

        // lokasi (soft semantic)
        'dekat kampus' => ['lokasi strategis'],
        'sekitar kampus' => ['lokasi strategis'],
    ];

    /**
     * Expand text dengan sinonim
     */
    public function expand(string $text): string
    {
        $expanded = $text;

        foreach ($this->synonyms as $key => $values) {
            if (str_contains($expanded, $key)) {
                foreach ($values as $synonym) {
                    $expanded .= ' ' . $synonym;
                }
            }
        }

        return trim($expanded);
    }
}
