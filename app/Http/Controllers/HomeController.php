<?php

namespace App\Http\Controllers;

use App\Models\Kost;

class HomeController extends Controller
{
    public function index()
    {
        $heroSlides = [
            '/img/slide1.jpg',
            '/img/slide2.jpg',
            '/img/slide3.jpg',
        ];

        // Kost Terbaru + Data Rating
        $kostTerbaru = Kost::latest()
            ->withAvg('ratings', 'rating')    // ratings_avg_rating
            ->withCount('ratings')            // ratings_count
            ->take(6)
            ->get();

        // Kost Rekomendasi + Rating
        $rekomendasi = Kost::where('is_recommended', true)
            ->orWhere('kota', 'Purwokerto')
            ->withAvg('ratings', 'rating')
            ->withCount('ratings')
            ->take(6)
            ->get();

        // Group area Purwokerto
        $areaPurwokerto = Kost::where('kota', 'Purwokerto')
            ->get()
            ->groupBy('kecamatan');

        return view('home', compact('heroSlides', 'kostTerbaru', 'rekomendasi', 'areaPurwokerto'));
    }
}
