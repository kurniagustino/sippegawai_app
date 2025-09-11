<?php

namespace App\Http\Controllers;

use App\Models\Berkas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Controller;

class PegawaiBerkasController extends Controller
{
    /**
     * Tampilkan daftar berkas milik pegawai yang sedang login.
     */
    public function index()
    {
        $pegawai = Auth::user()->pegawai;
        $berkas = $pegawai->berkas()->latest()->paginate(10);
        return view('berkas.pegawai_index', compact('berkas'));
    }

    /**
     * Tampilkan formulir untuk mengunggah berkas baru.
     */
    public function create()
    {
       // Mengambil semua jenis berkas yang sudah diunggah oleh pegawai yang sedang login
    $uploaded_berkas = auth()->user()->pegawai->berkas()->pluck('nama_berkas')->toArray();
    
    // Mengirim daftar ini ke tampilan
    return view('berkas.pegawai_create', compact('uploaded_berkas'));
    }

    /**
     * Simpan berkas yang diunggah.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_berkas' => 'required|string|max:100',
            'file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'tanggal_kadaluarsa' => 'nullable|date',
        ]);
        
        $pegawai = Auth::user()->pegawai;

        // Cek apakah berkas dengan nama yang sama sudah diunggah untuk pegawai ini
        $existingBerkas = $pegawai->berkas()->where('nama_berkas', $request->nama_berkas)->exists();

        if ($existingBerkas) {
            return redirect()->back()->withInput()->withErrors([
                'nama_berkas' => 'Berkas dengan jenis ini sudah diunggah.'
            ]);
        }

        $filePath = $request->file('file')->store('berkas_pegawai', 'public');

        $pegawai->berkas()->create([
            'nama_berkas' => $request->nama_berkas,
            'file_path' => $filePath,
            'tanggal_upload' => now(),
            'tanggal_kadaluarsa' => $request->tanggal_kadaluarsa,
        ]);

        return redirect()->route('pegawai.berkas.index')->with('success', 'Berkas berhasil di-upload.');
    }

    /**
     * Hapus berkas.
     */
    public function destroy(Berkas $berka)
    {
        // Pastikan berkas yang dihapus adalah milik user yang sedang login
        if ($berka->pegawai_id !== Auth::user()->pegawai->id) {
            abort(403, 'Anda tidak diizinkan menghapus berkas ini.');
        }

        Storage::disk('public')->delete($berka->file_path);
        $berka->delete();
        
        return redirect()->route('pegawai.berkas.index')->with('success', 'Berkas berhasil dihapus.');
    }
}