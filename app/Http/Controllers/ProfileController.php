<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function show()
    {
        return view('profile.user.profile');
    }
    public function riwayat()
    {
        $bookings = auth()->user()->bookings()->with('kost')->latest()->get();
        return view('profile.user.riwayat', compact('bookings'));
    }
    public function syarat()
    {
        return view('profile.user.syarat');
    }
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request)
    {
        $this->authorizeOwner();
        $user = $request->user();
        $kost = $user->kosts()->first();

        if (!$kost) {
            return redirect()->route('profile.create')->with('info', 'Silakan tambahkan kost terlebih dahulu.');
        }

        return view('profile.user.edit', [
            'user' => $user,
            'kost' => $kost,
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        Log::info('Profile update attempt', [
            'user_id' => $request->user()->id,
        ]);

        $user = $request->user();

        // Ambil data validasi
        $validated = $request->validated();

        // Pastikan email tidak bisa diubah dari form
        unset($validated['email']);

        // -------------------------
        // FOTO DIRI
        // -------------------------
        if ($request->hasFile('image')) {

            // Hapus foto lama jika ada
            if ($user->image && Storage::disk('public')->exists($user->image)) {
                Storage::disk('public')->delete($user->image);
            }

            $validated['image'] = $request->file('image')
                ->store('profiles', 'public');

            Log::info('Profile image uploaded', [
                'path' => $validated['image'],
            ]);
        }

        // -------------------------
        // FOTO IDENTITAS (KTP)
        // -------------------------
        if ($request->hasFile('image_id')) {

            if ($user->image_id && Storage::disk('public')->exists($user->image_id)) {
                Storage::disk('public')->delete($user->image_id);
            }

            $validated['image_id'] = $request->file('image_id')
                ->store('identities', 'public');

            Log::info('Identity image uploaded', [
                'path' => $validated['image_id'],
            ]);
        }

        // -------------------------
        // UPDATE USER
        // -------------------------
        $user->fill($validated);

        // Jika suatu saat email boleh diubah (future-proof)
        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        Log::info('Profile updated successfully', [
            'user_id' => $user->id,
        ]);

        return Redirect::route('profile.show')
            ->with('success', 'Profil berhasil diperbarui');
    }

    private function authorizeOwner()
    {
        if (Auth::user()->role !== 'owner') {
            abort(403, 'Unauthorized action.');
        }
    }

    public function dashboard()
    {
        $this->authorizeOwner();
        $user = Auth::user();

        // Ambil booking untuk kost yang dimiliki user ini
        $ownerBookings = \App\Models\Booking::whereHas('kost', function ($query) use ($user) {
            $query->where('owner_id', $user->id);
        })->with(['user', 'kost', 'payment'])->latest()->get();

        // Ambil data kost milik owner
        $kosts = \App\Models\Kost::where('owner_id', $user->id)->get();

        return view('profile.user.dashboard', [
            'user' => $user,
            'ownerBookings' => $ownerBookings,
            'kosts' => $kosts,
        ]);
    }

    public function create()
    {
        $this->authorizeOwner();
        // Kalau ini untuk halaman "lengkapi profil" misalnya
        return view('profile.user.create');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
