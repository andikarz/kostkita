<?php

namespace App\Http\Controllers;

use App\Models\KostPhoto;
use Illuminate\Support\Facades\Storage;

class KostPhotoController extends Controller
{
    public function destroy(KostPhoto $photo)
    {
        // opsional: pastikan hanya owner atau admin yang boleh hapus
        // abort_if($photo->kost->owner_id !== auth()->id(), 403);

        if ($photo->path) {
            Storage::disk('public')->delete($photo->path);
        }
        $kostId = $photo->kost_id;
        $photo->delete();

        return back()->with('success', 'Foto berhasil dihapus.');
    }
}
