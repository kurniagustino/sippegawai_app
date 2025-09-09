<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Pegawai;
use App\Models\Jabatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class PegawaiController extends Controller
{
    public function index()
    {
        // Ubah ini agar memuat relasi 'user' dan 'jabatan'
        $pegawai = Pegawai::with('user', 'jabatan')->latest()->paginate(10);
        return view('pegawai.index', compact('pegawai'));
    }

    /**
     * Menampilkan form untuk membuat profil pegawai baru.
     */
    public function create()
    {
        // Ambil semua data jabatan untuk form dropdown
        $jabatans = Jabatan::orderBy('nama_jabatan')->get();
        return view('pegawai.create', compact('jabatans'));
    }


    /**
     * Menyimpan data profil pegawai baru dan menautkannya ke user yang dipilih.
     */
    public function store(Request $request)
    {
        // Ubah validasi jabatan menjadi jabatan_id
        $request->validate([
            'nama' => 'required|string|max:100',
            'nip' => 'required|string|max:50|unique:pegawai,nip',
            'jabatan_id' => 'required|integer|exists:jabatan,id',
            'jenis_kelamin' => 'required|string',
            'username' => 'required|string|max:255|unique:users,username',
            'email' => 'nullable|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'nohp' => 'nullable|string|max:20',
            'tanggal_lahir' => 'nullable|date',
            'mulaikerja' => 'nullable|date',
            'alamat' => 'nullable|string',
            'catatan' => 'nullable|string',
        ]);

        DB::transaction(function () use ($request) {
            $user = User::create([
                'name' => $request->nama,
                'username' => $request->username,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            $pegawai = Pegawai::create([
                'idUser' => $user->id,
                'nama' => $request->nama,
                'nip' => $request->nip,
                'jabatan_id' => $request->jabatan_id, // Gunakan jabatan_id
                'jenis_kelamin' => $request->jenis_kelamin,
                'tempatlahir' => $request->tempatlahir,
                'tanggal_lahir' => $request->tanggal_lahir,
                'alamat' => $request->alamat,
                'mulaikerja' => $request->mulaikerja,
                'nohp' => $request->nohp,
                'catatan' => $request->catatan,
            ]);

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
        // Cek otorisasi: Izinkan jika user adalah admin, atau jika user adalah pemilik profil ini
        if (auth()->user()->role != 'admin' && auth()->user()->pegawai->id != $pegawai->id) {
            abort(403, 'Anda tidak memiliki akses untuk melihat profil ini.');
        }

        $pegawai->load('user', 'riwayatPendidikans', 'pelatihans', 'jabatan');
        return view('pegawai.show', compact('pegawai'));
    }

    public function edit(Pegawai $pegawai)
    {
        // Cek otorisasi: Izinkan jika user adalah admin, atau jika user adalah pemilik profil ini
        if (auth()->user()->role != 'admin' && auth()->user()->pegawai->id != $pegawai->id) {
            abort(403, 'Anda tidak memiliki akses untuk mengedit profil ini.');
        }

        $jabatans = Jabatan::orderBy('nama_jabatan')->get();
        return view('pegawai.edit', compact('pegawai', 'jabatans'));
    }

    public function update(Request $request, Pegawai $pegawai)
    {

        // Cek otorisasi: Izinkan jika user adalah admin, atau jika user adalah pemilik profil ini
        if (auth()->user()->role != 'admin' && auth()->user()->pegawai->id != $pegawai->id) {
            abort(403, 'Anda tidak memiliki akses untuk mengupdate profil ini.');
        }

        // Ubah validasi jabatan menjadi jabatan_id
        $request->validate([
            'nama' => 'required|string|max:100',
            'nip' => 'required|string|max:50|unique:pegawai,nip,' . $pegawai->id,
            'jabatan_id' => 'required|integer|exists:jabatan,id', // Validasi baru
            'jenis_kelamin' => 'required|string',
            'nohp' => 'nullable|string|max:20',
            'tanggal_lahir' => 'nullable|date',
            'mulaikerja' => 'nullable|date',
            'alamat' => 'nullable|string',
            'catatan' => 'nullable|string',
        ]);

        DB::transaction(function () use ($request, $pegawai) {
            $pegawai->update([
                'nama' => $request->nama,
                'nip' => $request->nip,
                'jabatan_id' => $request->jabatan_id, // Gunakan jabatan_id
                'jenis_kelamin' => $request->jenis_kelamin,
                'tempatlahir' => $request->tempatlahir,
                'tanggal_lahir' => $request->tanggal_lahir,
                'alamat' => $request->alamat,
                'mulaikerja' => $request->mulaikerja,
                'nohp' => $request->nohp,
                'catatan' => $request->catatan,
            ]);
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
