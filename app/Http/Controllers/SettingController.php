<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class SettingController extends Controller
{
    public function index()
    {
        $qrisPath = $this->getQrisPath();
        return view('settings.index', compact('qrisPath'));
    }

    public function uploadQris(Request $request)
    {
        $request->validate([
            'qris_image' => 'required|image|mimes:png,jpg,jpeg|max:2048',
        ]);

        $directory = public_path('storage/qris');

        // Buat directory jika belum ada
        if (!File::isDirectory($directory)) {
            File::makeDirectory($directory, 0755, true);
        }

        // Hapus gambar QRIS lama jika ada
        $files = File::files($directory);
        foreach ($files as $file) {
            if ($file->getExtension() === 'png' || $file->getExtension() === 'jpg' || $file->getExtension() === 'jpeg') {
                File::delete($file->getPathname());
            }
        }

        // Simpan gambar baru
        $filename = 'qris.' . $request->file('qris_image')->getClientOriginalExtension();
        $request->file('qris_image')->move($directory, $filename);

        return redirect()->route('settings.index')
            ->with('success', 'Gambar QRIS berhasil diunggah.');
    }

    public function deleteQris()
    {
        $directory = public_path('storage/qris');
        $files = File::files($directory);

        foreach ($files as $file) {
            if (in_array(strtolower($file->getExtension()), ['png', 'jpg', 'jpeg'])) {
                File::delete($file->getPathname());
            }
        }

        return redirect()->route('settings.index')
            ->with('success', 'Gambar QRIS berhasil dihapus.');
    }

    private function getQrisPath(): ?string
    {
        $directory = public_path('storage/qris');

        if (!File::isDirectory($directory)) {
            return null;
        }

        $files = File::files($directory);
        foreach ($files as $file) {
            if (in_array(strtolower($file->getExtension()), ['png', 'jpg', 'jpeg'])) {
                return 'storage/qris/' . $file->getFilename();
            }
        }

        return null;
    }
}
