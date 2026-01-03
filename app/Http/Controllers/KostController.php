<?php

namespace App\Http\Controllers;

use App\Models\Kost;
use App\Models\KostPhoto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class KostController extends Controller
{
    /** Detail kost + rekomendasi di kecamatan yang sama */
    public function show(Kost $kost)
    {
        // Eager load rating & user
        $kost->load(['ratings.user']);

        $related = Kost::where('kecamatan', $kost->kecamatan)
            ->whereKeyNot($kost->getKey())
            ->latest()
            ->take(4)
            ->get();

        return view('kost.show', compact('kost', 'related'));
    }



    /** LIST admin */
    public function index(Request $request)
    {
        $query = Kost::query();

        // keyword: nama / alamat / kecamatan
        if ($request->filled('q')) {
            $keyword = trim($request->q);
            $query->where(function ($q) use ($keyword) {
                $q->where('nama', 'like', "%{$keyword}%")
                    ->orWhere('alamat', 'like', "%{$keyword}%")
                    ->orWhere('kecamatan', 'like', "%{$keyword}%");
            });
        }

        // filter area (kecamatan)
        if ($request->filled('area')) {
            $query->where('kecamatan', $request->area);
        }

        // filter harga max (input boleh dalam format rupiah: "Rp 1.500.000")
        $hargaMax = null;
        if ($request->filled('harga_max')) {
            $hargaMax = (int) preg_replace("/[^0-9]/", "", $request->harga_max);

            if ($hargaMax > 0) {
                $query->where('harga_bulanan', '<=', $hargaMax);
            }
        }

        // urutkan terbaru
        $kosts = $query->latest()->paginate(10)->appends($request->query());

        return view('admin.kost.index', compact('kosts'));
    }

    /** FORM Tambah */
    public function create()
    {
        // penting: kirim $kost kosong ke form (biar nggak undefined di old('...', $kost->...)
        $kost = new Kost();

        return view('admin.kost.create', compact('kost'));
    }

    /** SIMPAN Kost (CREATE) */
    public function store(Request $request)
    {
        $data = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'jenis' => ['required', 'in:putra,putri,campur'],
            'alamat' => ['required', 'string', 'max:500'],
            'kecamatan' => ['required', 'string', 'max:100'],
            'kota' => ['nullable', 'string', 'max:100'],
            'harga_bulanan' => ['required', 'integer', 'min:0'],
            'stok_kamar' => ['required', 'integer', 'min:0'],
            'deskripsi' => ['nullable', 'string'],

            'ukuran_kamar' => ['required', 'in:2x3,3x3,3x4,3x5'],
            'listrik_status' => ['required', 'in:termasuk,tidak'],

            'fasilitas' => ['nullable', 'array'],
            'fasilitas.*' => ['string'],
            'fasilitas_kmandi' => ['nullable', 'array'],
            'fasilitas_kmandi.*' => ['string'],
            'fasilitas_umum' => ['nullable', 'array'],
            'fasilitas_umum.*' => ['string'],

            'is_recommended' => ['nullable', 'boolean'],

            'cover' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'photos' => ['nullable', 'array'],
            'photos.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ]);

        // cover
        if ($request->hasFile('cover')) {
            $data['cover'] = $request->file('cover')->store('covers', 'public');
        }

        // kota default
        if (empty($data['kota'])) {
            $data['kota'] = 'Purwokerto';
        }

        // checkbox jadi array
        $data['fasilitas'] = $request->input('fasilitas', []);
        $data['fasilitas_kmandi'] = $request->input('fasilitas_kmandi', []);
        $data['fasilitas_umum'] = $request->input('fasilitas_umum', []);

        $data['is_recommended'] = $request->boolean('is_recommended');
        $data['owner_id'] = Auth::id();

        $kost = Kost::create($data);

        // simpan foto tambahan
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $file) {
                $path = $file->store('photos', 'public');
                KostPhoto::create([
                    'kost_id' => $kost->id,
                    'path' => $path,
                ]);
            }
        }

        return redirect()
            ->route('kost.show.id', $kost->id)
            ->with('success', 'Kost berhasil ditambahkan!');
    }

    /** UPDATE Kost */
    public function update(Request $request, Kost $kost)
    {
        $data = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'jenis' => ['required', 'in:putra,putri,campur'],
            'alamat' => ['required', 'string', 'max:500'],
            'kecamatan' => ['required', 'string', 'max:100'],
            'kota' => ['nullable', 'string', 'max:100'],
            'harga_bulanan' => ['required', 'integer', 'min:0'],
            'stok_kamar' => ['required', 'integer', 'min:0'],
            'deskripsi' => ['nullable', 'string'],

            'ukuran_kamar' => ['required', 'in:2x3,3x3,3x4,3x5'],
            'listrik_status' => ['required', 'in:termasuk,tidak'],

            'fasilitas' => ['nullable', 'array'],
            'fasilitas.*' => ['string'],
            'fasilitas_kmandi' => ['nullable', 'array'],
            'fasilitas_kmandi.*' => ['string'],
            'fasilitas_umum' => ['nullable', 'array'],
            'fasilitas_umum.*' => ['string'],

            'is_recommended' => ['nullable', 'boolean'],

            'cover' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'photos' => ['nullable', 'array'],
            'photos.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ]);

        // handle cover
        if ($request->hasFile('cover')) {
            if ($kost->cover) {
                Storage::disk('public')->delete($kost->cover);
            }
            $data['cover'] = $request->file('cover')->store('covers', 'public');
        } else {
            unset($data['cover']);
        }

        // kota default
        if (empty($data['kota'])) {
            $data['kota'] = 'Purwokerto';
        }

        // checkbox array
        $data['fasilitas'] = $request->input('fasilitas', []);
        $data['fasilitas_kmandi'] = $request->input('fasilitas_kmandi', []);
        $data['fasilitas_umum'] = $request->input('fasilitas_umum', []);
        $data['is_recommended'] = $request->boolean('is_recommended');

        // jangan create baru, tapi UPDATE
        $kost->update($data);

        // tambah foto-foto baru (kalau ada)
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $file) {
                $path = $file->store('photos', 'public');
                KostPhoto::create([
                    'kost_id' => $kost->id,
                    'path' => $path,
                ]);
            }
        }

        // Redirect based on role/guard
        if (Auth::guard('admin')->check()) {
            return redirect()
                ->route('admin.kost.edit', $kost->id)
                ->with('success', 'Kost berhasil diperbarui!');
        }

        // Default: Owner
        return redirect()
            ->route('profile.edit')
            ->with('success', 'Kost berhasil diperbarui!');
    }

    public function edit(Kost $kost)
    {
        // Jika admin, kembalikan view admin
        if (Auth::guard('admin')->check()) {
            return view('admin.kost.edit', compact('kost'));
        }

        // Jika user biasa (owner), pastikan dia pemiliknya
        if ($kost->owner_id !== Auth::id()) {
            abort(403);
        }

        return view('profile.user.edit', compact('kost'));
    }

    /** HAPUS Kost */
    public function destroy(Kost $kost)
    {
        if ($kost->cover) {
            Storage::disk('public')->delete($kost->cover);
        }

        $kost->delete();

        return back()->with('success', 'Kost berhasil dihapus!');
    }
    public function updateStock(Request $request, Kost $kost)
    {
        // Pastikan owner yang mengubah
        if ($kost->owner_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'stok_kamar' => 'required|integer|min:0',
        ]);

        $kost->update(['stok_kamar' => $request->stok_kamar]);

        return back()->with('success', 'Stok kamar berhasil diperbarui!');
    }
}
