<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pegawai;
use App\Models\Jabatan;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfilePegawaiController extends Controller
{
    /**
     * Display the specified resource.
     */
    public function show()
    {
        $pegawai = auth()->user()->pegawai;
        $pegawai->load('user', 'riwayatPendidikans', 'pelatihans', 'jabatan');
        return view('pegawai.profile', compact('pegawai'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit()
    {
        $pegawai = auth()->user()->pegawai;
        $jabatans = Jabatan::all();
        return view('pegawai.edit_profile', compact('pegawai', 'jabatans'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $pegawai = auth()->user()->pegawai;
        $user = $pegawai->user;

        $request->validate([
            'nama' => 'sometimes|required|string|max:100',
            'nip' => 'sometimes|required|string|max:50|unique:pegawai,nip,' . $pegawai->id,
            'jenis_kelamin' => 'sometimes|required|string',
            'jabatan_id' => 'sometimes|required|integer|exists:jabatan,id',
            'tempatlahir' => 'nullable|string|max:100',
            'tanggal_lahir' => 'nullable|date',
            'alamat' => 'nullable|string|max:255',
            'nohp' => 'nullable|string|max:20',
            'mulaikerja' => 'nullable|date',
            'catatan' => 'nullable|string',
            'username' => 'sometimes|required|string|max:255|unique:users,username,' . $user->id,
            'email' => 'nullable|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // ==========================================================
        //         MENERAPKAN LOGIKA DARI REFERENSI ANDA
        // ==========================================================
        
        // 1. Siapkan dulu data teks yang akan diupdate
        $dataToUpdate = $request->except(['_token', '_method', 'foto', 'pendidikan', 'pelatihan', 'password_confirmation']);

        // 2. Cek jika ada file foto baru yang di-upload
        if ($request->hasFile('foto')) {
            // Hapus foto lama dari storage jika ada
            if ($pegawai->foto) {
                Storage::disk('public')->delete($pegawai->foto);
            }

            // Upload file baru dan dapatkan path-nya
            $newPhotoPath = $request->file('foto')->store('foto-pegawai', 'public');

            // Tambahkan path baru ke data yang akan di-update
            $dataToUpdate['foto'] = $newPhotoPath;
        }

        // 3. Lakukan semua update dalam satu transaksi
        DB::transaction(function () use ($request, $pegawai, $user, $dataToUpdate) {
            
            // Update data pegawai (termasuk path foto baru jika ada)
            $pegawai->update($dataToUpdate);

            // Update data user jika ada
            if ($request->has('username')) {
                $userData = $request->only(['username', 'email']);
                if ($request->filled('password')) {
                    $userData['password'] = Hash::make($request->password);
                }
                $user->update($userData);
                $user->name = $request->nama;
                $user->save();
            }

            // Update riwayat
            if ($request->has('pendidikan')) {
                $pegawai->riwayatPendidikans()->delete();
                foreach ($request->pendidikan as $pendidikan) {
                    if (!empty($pendidikan['nama_institusi'])) {
                        $pegawai->riwayatPendidikans()->create($pendidikan);
                    }
                }
            }
            if ($request->has('pelatihan')) {
                $pegawai->pelatihans()->delete();
                foreach ($request->pelatihan as $pelatihan) {
                    if (!empty($pelatihan['nama_pelatihan'])) {
                        $pegawai->pelatihans()->create($pelatihan);
                    }
                }
            }
        });

        return redirect()->route('pegawai.profile.show')->with('success', 'Profil Anda berhasil diperbarui.');
    }
}