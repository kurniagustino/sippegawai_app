<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pegawai;
use App\Models\Jabatan;
use App\Models\User; // Tambahkan model User
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ProfilePegawaiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     * Perbaikan: Menghilangkan parameter $id karena rute tidak mengirimkannya.
     */
    public function show()
    {
        $pegawai = auth()->user()->pegawai;
        $pegawai->load('user', 'riwayatPendidikans', 'pelatihans', 'jabatan');

        return view('pegawai.profile', compact('pegawai'));
    }

    /**
     * Show the form for editing the specified resource.
     * Perbaikan: Menghilangkan parameter $id karena rute tidak mengirimkannya.
     */
    public function edit()
    {
        $pegawai = auth()->user()->pegawai;
        $jabatans = Jabatan::all();
        return view('pegawai.edit_profile', compact('pegawai', 'jabatans'));
    }

    /**
     * Update the specified resource in storage.
     * Perbaikan: Menghilangkan parameter $id karena rute tidak mengirimkannya.
     */
    public function update(Request $request)
    {
        $pegawai = auth()->user()->pegawai;
        $user = $pegawai->user;

        $request->validate([
            'nama' => 'required|string|max:100',
            'nip' => 'required|string|max:50|unique:pegawai,nip,' . $pegawai->id,
            'jenis_kelamin' => 'required|string',
            'jabatan_id' => 'required|integer|exists:jabatan,id', // Tambahkan validasi jabatan
            'tempatlahir' => 'nullable|string|max:100',
            'tanggal_lahir' => 'nullable|date',
            'alamat' => 'nullable|string|max:255',
            'nohp' => 'nullable|string|max:20',
            'mulaikerja' => 'nullable|date',
            'catatan' => 'nullable|string',
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
            'email' => 'nullable|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        DB::transaction(function () use ($request, $pegawai, $user) {
            // Update data Pegawai
            $pegawai->update([
                'nama' => $request->nama,
                'nip' => $request->nip,
                'jenis_kelamin' => $request->jenis_kelamin,
                'jabatan_id' => $request->jabatan_id,
                'tempatlahir' => $request->tempatlahir,
                'tanggal_lahir' => $request->tanggal_lahir,
                'alamat' => $request->alamat,
                'mulaikerja' => $request->mulaikerja,
                'nohp' => $request->nohp,
                'catatan' => $request->catatan,
            ]);

            // Update data User
            $userData = [
                'username' => $request->username,
                'email' => $request->email,
            ];
            // Cek jika ada password baru yang diisi
            if ($request->filled('password')) {
                $userData['password'] = Hash::make($request->password);
            }
            $user->update($userData);

            // Update riwayat pendidikan
            $pegawai->riwayatPendidikans()->delete();
            if ($request->has('pendidikan')) {
                foreach ($request->pendidikan as $pendidikan) {
                    if (!empty($pendidikan['nama_institusi'])) {
                        $pegawai->riwayatPendidikans()->create($pendidikan);
                    }
                }
            }

            // Update riwayat pelatihan
            $pegawai->pelatihans()->delete();
            if ($request->has('pelatihan')) {
                foreach ($request->pelatihan as $pelatihan) {
                    if (!empty($pelatihan['nama_pelatihan'])) {
                        $pegawai->pelatihans()->create($pelatihan);
                    }
                }
            }
        });

        return redirect()->route('pegawai.profile.show')->with('success', 'Profil Anda berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}