<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Pegawai;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class PegawaiDashboardController extends Controller
{
    /**
     * Menampilkan halaman dashboard pegawai.
     */
    public function dashboard()
    {
        $pegawai = Auth::user()->pegawai;

        // Ganti 'pegawai.dashboard' menjadi 'dashboard.pegawai'
        return view('dashboard.pegawai', compact('pegawai'));
    }
    /**
     * Menampilkan halaman profil pegawai.
     */
    public function showProfile()
    {
        // Ambil ID user yang sedang login
        $userId = Auth::id();

        // Cari data pegawai yang terhubung dengan user tersebut
        // Menggunakan with('jabatan') untuk memuat data jabatan
        $pegawai = Pegawai::with('jabatan', 'riwayatPendidikans', 'pelatihans')
                            ->where('idUser', $userId)
                            ->firstOrFail();
                            
        // Kirim data pegawai ke view
        return view('pegawai.profile', compact('pegawai'));
    }

    /**
     * Memperbarui informasi profil pegawai.
     */
    public function updateProfile(Request $request)
    {
        $pegawai = Auth::user()->pegawai;
        $user = Auth::user();

        // Validasi data yang boleh diubah oleh pegawai
        $request->validate([
            'nama' => 'required|string|max:100',
            'nohp' => 'nullable|string|max:20',
            'tempatlahir' => 'nullable|string|max:100',
            'tanggal_lahir' => 'nullable|date',
            'alamat' => 'nullable|string',
        ]);

        // Perbarui data di tabel 'pegawai'
        $pegawai->update([
            'nama' => $request->nama,
            'nohp' => $request->nohp,
            'tempatlahir' => $request->tempatlahir,
            'tanggal_lahir' => $request->tanggal_lahir,
            'alamat' => $request->alamat,
        ]);
        
        // Perbarui juga nama di tabel 'users' agar sinkron
        $user->update([
            'name' => $request->nama
        ]);

        return redirect()->route('pegawai.profile.show')->with('success', 'Profil berhasil diperbarui!');
    }

    /**
     * Menampilkan halaman untuk mengganti password.
     */
    public function showChangePasswordForm()
    {
        return view('pegawai.password');
    }

    /**
     * Memperbarui password pegawai.
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'string', function ($attribute, $value, $fail) {
                if (!Hash::check($value, Auth::user()->password)) {
                    return $fail(__('Password saat ini salah.'));
                }
            }],
            'password' => 'required|string|min:8|confirmed',
        ]);

        Auth::user()->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('pegawai.password.show')->with('success', 'Password berhasil diperbarui!');
    }
}