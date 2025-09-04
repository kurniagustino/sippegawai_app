<?php

namespace App\Http\Controllers;

use App\Models\Berkas;
use App\Models\Pegawai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BerkasController extends Controller
{
    public function index()
    {
        $berkas = Berkas::with('pegawai')->latest()->paginate(10);
        return view('berkas.index', compact('berkas'));
    }

    public function create()
    {
        $pegawai = Pegawai::orderBy('nama')->get();
        return view('berkas.create', compact('pegawai'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'pegawai_id' => 'required|exists:pegawai,id',
            'nama_berkas' => 'required|string|max:100',
            'file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'tanggal_kadaluarsa' => 'nullable|date',
        ]);

        // Menggunakan cara yang benar untuk menyimpan file di disk 'public'
        $filePath = $request->file('file')->store('berkas_pegawai', 'public');

        Berkas::create([
            'pegawai_id' => $request->pegawai_id,
            'nama_berkas' => $request->nama_berkas,
            'file_path' => $filePath,
            'tanggal_upload' => now(),
            'tanggal_kadaluarsa' => $request->tanggal_kadaluarsa,
        ]);

        return redirect()->route('berkas.index')->with('success', 'Berkas berhasil di-upload.');
    }

    public function show(Berkas $berka)
    {
        return view('berkas.show', compact('berka'));
    }

    /**
     * Menampilkan form untuk mengedit informasi berkas.
     * (METHOD BARU)
     */
    public function edit(Berkas $berka)
    {
        $pegawai = Pegawai::orderBy('nama')->get(); // Ambil daftar pegawai untuk dropdown
        return view('berkas.edit', compact('berka', 'pegawai'));
    }

    /**
     * Memperbarui informasi berkas di database.
     * (METHOD BARU)
     */
    public function update(Request $request, Berkas $berka)
    {
        $request->validate([
            'pegawai_id' => 'required|exists:pegawai,id',
            'nama_berkas' => 'required|string|max:100',
            'tanggal_kadaluarsa' => 'nullable|date',
            'file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048', // File baru bersifat opsional
        ]);

        $dataToUpdate = $request->except('file');

        // Cek jika ada file baru yang di-upload
        if ($request->hasFile('file')) {
            // 1. Hapus file lama dari storage
            Storage::disk('public')->delete($berka->file_path);

            // 2. Upload file baru dan dapatkan path-nya
            $newFilePath = $request->file('file')->store('berkas_pegawai', 'public');

            // 3. Tambahkan path baru ke data yang akan di-update
            $dataToUpdate['file_path'] = $newFilePath;
        }

        $berka->update($dataToUpdate);

        return redirect()->route('berkas.index')->with('success', 'Informasi berkas berhasil diperbarui.');
    }

    public function destroy(Berkas $berka)
    {
        // Gunakan disk 'public' untuk menghapus file
        Storage::disk('public')->delete($berka->file_path);
        
        $berka->delete();
        
        return redirect()->route('berkas.index')->with('success', 'Berkas berhasil dihapus.');
    }
}