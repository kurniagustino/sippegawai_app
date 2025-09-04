<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Pegawai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PegawaiController extends Controller
{
    public function index()
    {
        $pegawai = Pegawai::with('user')->latest()->paginate(10);
        return view('pegawai.index', compact('pegawai'));
    }

    /**
     * Menampilkan form untuk membuat profil pegawai baru.
     */
    public function create()
    {
        // Mengambil semua user yang BELUM memiliki relasi dengan pegawai
        $users = User::whereDoesntHave('pegawai')->get();
        return view('pegawai.create', compact('users'));
    }

    /**
     * Menyimpan data profil pegawai baru dan menautkannya ke user yang dipilih.
     */
    public function store(Request $request)
    {
        // Validasi untuk semua field
        $request->validate([
            'idUser' => 'required|integer|unique:pegawai,idUser',
            'nama' => 'required|string|max:100',
            'nip' => 'required|string|max:50|unique:pegawai,nip',
            'jabatan' => 'required|string',
            'jenis_kelamin' => 'required|string',
            'nohp' => 'nullable|string|max:20',
            'tanggal_lahir' => 'nullable|date',
            'mulaikerja' => 'nullable|date',
        ]);

        DB::transaction(function () use ($request) {
            // 1. Langsung buat data pegawai berdasarkan user yang dipilih dari dropdown
            $pegawai = Pegawai::create([
                'idUser' => $request->idUser,
                'nama' => $request->nama,
                'nip' => $request->nip,
                'jabatan' => $request->jabatan,
                'jenis_kelamin' => $request->jenis_kelamin,
                'tempatlahir' => $request->tempatlahir,
                'tanggal_lahir' => $request->tanggal_lahir,
                'alamat' => $request->alamat,
                'mulaikerja' => $request->mulaikerja,
                'nohp' => $request->nohp,
                'catatan' => $request->catatan,
            ]);

            // 2. Update 'name' di tabel user agar sama dengan nama pegawai (untuk konsistensi)
            $user = User::find($request->idUser);
            if ($user) {
                $user->name = $request->nama;
                $user->save();
            }

            // 3. Simpan riwayat pendidikan jika ada
            if ($request->has('pendidikan')) {
                foreach ($request->pendidikan as $pendidikan) {
                    if (!empty($pendidikan['nama_institusi'])) {
                        $pegawai->riwayatPendidikans()->create($pendidikan);
                    }
                }
            }

            // 4. Simpan riwayat pelatihan jika ada
            if ($request->has('pelatihan')) {
                foreach ($request->pelatihan as $pelatihan) {
                    if (!empty($pelatihan['nama_pelatihan'])) {
                        $pegawai->pelatihans()->create($pelatihan);
                    }
                }
            }
        });

        return redirect()->route('pegawai.index')->with('success', 'Data pegawai berhasil ditambahkan.');
    }

     /**
     * Menampilkan halaman detail untuk satu pegawai.
     * INI METHOD YANG BARU DITAMBAHKAN
     */
    public function show(Pegawai $pegawai)
    {
        // Memuat semua relasi yang dibutuhkan untuk halaman detail
        $pegawai->load('user', 'riwayatPendidikans', 'pelatihans');
        return view('pegawai.show', compact('pegawai'));
    }

    public function edit(Pegawai $pegawai)
    {
        $pegawai->load('user', 'riwayatPendidikans', 'pelatihans');
        return view('pegawai.edit', compact('pegawai'));
    }

    public function update(Request $request, Pegawai $pegawai)
    {
        $request->validate([
            'nama' => 'required|string|max:100',
            'nip' => 'required|string|max:50|unique:pegawai,nip,' . $pegawai->id,
            'jabatan' => 'required|string',
            'jenis_kelamin' => 'required|string',
            'nohp' => 'nullable|string|max:20',
            'tanggal_lahir' => 'nullable|date',
            'mulaikerja' => 'nullable|date',
        ]);

        DB::transaction(function () use ($request, $pegawai) {
            $pegawai->update($request->all());
            $pegawai->user->update(['name' => $request->nama]);

            $pegawai->riwayatPendidikans()->delete();
            if ($request->has('pendidikan')) {
                foreach ($request->pendidikan as $pendidikan) {
                    if (!empty($pendidikan['nama_institusi'])) {
                        $pegawai->riwayatPendidikans()->create($pendidikan);
                    }
                }
            }

            $pegawai->pelatihans()->delete();
            if ($request->has('pelatihan')) {
                foreach ($request->pelatihan as $pelatihan) {
                     if (!empty($pelatihan['nama_pelatihan'])) {
                        $pegawai->pelatihans()->create($pelatihan);
                    }
                }
            }
        });

        return redirect()->route('pegawai.index')->with('success', 'Data pegawai berhasil diperbarui.');
    }

    public function destroy(Pegawai $pegawai)
    {
        // Opsi: Hanya hapus profil pegawai, bukan user-nya.
        $pegawai->delete();
        return redirect()->route('pegawai.index')->with('success', 'Profil pegawai berhasil dihapus. Akun user tetap ada.');
    }
}

