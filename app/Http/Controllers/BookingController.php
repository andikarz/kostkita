<?php

namespace App\Http\Controllers;

use App\Models\Kost;
use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    public function create(Kost $kost)
    {
        if ($kost->stok_kamar <= 0) {
            return back()->with('error', 'Maaf, stok kamar untuk kost ini sudah habis.');
        }

        return view('booking.create', compact('kost'));
    }

    public function store(Request $request, Kost $kost)
    {
        if ($kost->stok_kamar <= 0) {
            return back()->with('error', 'Maaf, stok kamar untuk kost ini sudah habis.');
        }

        $request->validate([
            'tanggal_mulai' => 'required|date|after_or_equal:today',
            'lama_sewa' => 'required|integer|min:1'
        ]);

        $biaya_layanan = 25000;
        $total = ($kost->harga_bulanan * $request->lama_sewa) + $biaya_layanan;

        $booking = Booking::create([
            'user_id' => Auth::id(),
            'kost_id' => $kost->id,
            'tanggal_mulai' => $request->tanggal_mulai,
            'lama_sewa' => $request->lama_sewa,
            'harga_per_bulan' => $kost->harga_bulanan,
            'pajak' => 0,
            'biaya_layanan' => $biaya_layanan,
            'total' => $total,
            'status' => 'menunggu_pembayaran',
        ]);

        return redirect()
            ->route('payment.checkout', $booking)
            ->with('success', 'Booking berhasil dibuat, silakan lakukan pembayaran.');
    }

    public function print(Booking $booking)
    {
        $this->authorizeBooking($booking);

        if ($booking->status !== 'paid') {
            abort(403, 'Hanya booking yang sudah dibayar yang dapat dicetak.');
        }

        return view('booking.print', compact('booking'));
    }

    public function payment(Booking $booking)
    {
        $this->authorizeBooking($booking);

        return redirect()->route('payment.checkout', $booking);
    }

    public function paymentStore(Request $request, Booking $booking)
    {
        $this->authorizeBooking($booking);

        $data = $request->validate([
            'bukti' => ['required', 'image', 'max:4096'],
        ]);

        $path = $request->file('bukti')->store('bukti', 'public');

        Payment::updateOrCreate(
            ['booking_id' => $booking->id],
            [
                'bukti' => $path,
                'status' => 'pending',
            ]
        );

        // update status booking
        $booking->update(['status' => 'menunggu_konfirmasi']);

        return redirect()
            ->route('booking.payment', $booking)
            ->with('success', 'Bukti pembayaran berhasil diupload. Tunggu verifikasi admin.');
    }

    private function authorizeBooking(Booking $booking)
    {
        abort_if($booking->user_id !== Auth::id(), 403);
    }
}

