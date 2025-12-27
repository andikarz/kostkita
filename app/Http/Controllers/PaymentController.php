<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Http\Request;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Notification;

class PaymentController extends Controller
{
    public function __construct()
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');
        Config::$curlOptions = [
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_HTTPHEADER => [] // Midtrans SDK might expect this key to exist
        ];
    }

    public function checkout(Booking $booking)
    {
        // Ensure the booking belongs to the authenticated user
        if ($booking->user_id !== auth()->id()) {
            abort(403);
        }

        // Check for existing pending payment
        $payment = Payment::where('booking_id', $booking->id)->first();

        if (!$payment) {
            $payment = Payment::create([
                'booking_id' => $booking->id,
                'user_id' => auth()->id(),
                'jumlah' => $booking->total,
                'status' => 'pending',
            ]);
        }

        // Check if server key is set
        if (empty(Config::$serverKey)) {
            return back()->with('error', 'Midtrans Server Key belum dikonfigurasi. Silakan cek file .env Anda.');
        }

        // Check if server key is set
        if (empty(Config::$serverKey)) {
            return back()->with('error', 'Midtrans Server Key belum dikonfigurasi. Silakan cek file .env Anda.');
        }

        // Regenerate snap token if missing or if previous payment was not successful/pending
        if (empty($payment->snap_token) || $payment->status === 'pending') {
            $params = [
                'transaction_details' => [
                    'order_id' => 'BOOKING-' . $booking->id . '-' . time(),
                    'gross_amount' => (int) $booking->total,
                ],
                'customer_details' => [
                    'first_name' => auth()->user()->name,
                    'email' => auth()->user()->email,
                    'phone' => auth()->user()->phone,
                ],
            ];

            try {
                $snapToken = Snap::getSnapToken($params);
                $payment->update(['snap_token' => $snapToken]);
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('Midtrans Error: ' . $e->getMessage());
                \Illuminate\Support\Facades\Log::error('Params: ' . json_encode($params));
                \Illuminate\Support\Facades\Log::error($e->getTraceAsString());
                return back()->with('error', 'Terjadi kesalahan saat memproses pembayaran: ' . $e->getMessage());
            }
        }

        return view('payment.checkout', compact('booking', 'payment'));
    }

    public function notification(Request $request)
    {
        try {
            $notif = new Notification();
        } catch (\Exception $e) {
            return response()->json(['message' => 'Invalid notification'], 400);
        }

        $transaction = $notif->transaction_status;
        $type = $notif->payment_type;
        $orderId = $notif->order_id;
        $fraud = $notif->fraud_status;

        // Extract booking ID from order_id (format: BOOKING-{id}-{timestamp})
        $parts = explode('-', $orderId);
        $bookingId = $parts[1] ?? null;

        if (!$bookingId) {
            return response()->json(['message' => 'Invalid Order ID'], 400);
        }

        $payment = Payment::where('booking_id', $bookingId)->first();

        if (!$payment) {
            return response()->json(['message' => 'Payment not found'], 404);
        }

        $payment->transaction_id = $notif->transaction_id;
        $payment->payment_type = $type;

        if ($transaction == 'capture') {
            if ($type == 'credit_card') {
                if ($fraud == 'challenge') {
                    $payment->status = 'challenge';
                } else {
                    $payment->status = 'success';
                }
            }
        } else if ($transaction == 'settlement') {
            $payment->status = 'success';
        } else if ($transaction == 'pending') {
            $payment->status = 'pending';
        } else if ($transaction == 'deny') {
            $payment->status = 'failed';
        } else if ($transaction == 'expire') {
            $payment->status = 'expired';
        } else if ($transaction == 'cancel') {
            $payment->status = 'cancelled';
        }

        $payment->save();

        // Update Booking Status
        if ($payment->status === 'success') {
            $payment->booking->update(['status' => 'paid']); // Assuming booking has 'paid' status or similar
        } elseif (in_array($payment->status, ['failed', 'expired', 'cancelled'])) {
            $payment->booking->update(['status' => 'cancelled']);
        }

        return response()->json(['message' => 'Notification processed']);
    }

    public function finish(Request $request)
    {
        return redirect()->route('profile.riwayat')->with('success', 'Pembayaran sedang diproses.');
    }
}
