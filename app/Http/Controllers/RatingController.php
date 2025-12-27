<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Rating;
use Illuminate\Http\Request;

class RatingController extends Controller
{
    public function store(Request $request, Booking $booking)
    {
        // Pastikan booking milik user yang login
        if ($booking->user_id !== auth()->id()) {
            abort(403, 'Tidak boleh memberi rating booking orang lain.');
        }

        // Pastikan status sudah paid (ganti sesuai field kamu)
        if ($booking->status !== 'paid') {
            return back()->withErrors('Kamu hanya bisa memberi rating setelah pembayaran selesai.');
        }

        $data = $request->validate([
            'rating'  => ['required', 'integer', 'min:1', 'max:5'],
            'comment' => ['nullable', 'string', 'max:1000'],
        ]);

        Rating::updateOrCreate(
            [
                'user_id'    => auth()->id(),
                'kost_id'    => $booking->kost_id,
                'booking_id' => $booking->id,
            ],
            $data
        );

        return back()->with('success', 'Terima kasih, rating berhasil dikirim!');
    }
}
