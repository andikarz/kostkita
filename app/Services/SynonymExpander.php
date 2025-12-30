<?php

namespace App\Services;

class SynonymExpander
{
    /**
     * Kamus sinonim domain kost
     */
    protected array $synonyms = [

        // kamar mandi
        'wc' => ['kamar mandi'],
        'toilet' => ['kamar mandi'],
        'km' => ['kamar mandi'],
        'km dalam' => ['kamar mandi dalam'],
        'wc dalam' => ['kamar mandi dalam'],

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
