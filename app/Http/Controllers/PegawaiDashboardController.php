<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class PegawaiDashboardController extends Controller
{
    // ==========================================================
    //     TAMBAHKAN METHOD BARU INI UNTUK MENANGANI DASHBOARD
    // ==========================================================
    /**
     * Menampilkan halaman dashboard untuk pegawai yang sedang login.
     */
   public function dashboard()
    {
        // Dengan relasi yang benar, ini akan berfungsi
        $pegawai = Auth::user()->pegawai;

        if (!$pegawai) {
            abort(404, 'Data pegawai tidak ditemukan untuk user ini.');
        }

        // Ambil data untuk Stat Box
        $totalBerkas = $pegawai->berkas()->count();

        $berkasHampirKadaluarsa = $pegawai->berkas()
            ->whereNotNull('tanggal_kadaluarsa')
            ->where('tanggal_kadaluarsa', '>=', now())
            ->where('tanggal_kadaluarsa', '<=', now()->addDays(90))
            ->count();

        $berkasSudahKadaluarsa = $pegawai->berkas()
            ->whereNotNull('tanggal_kadaluarsa')
            ->where('tanggal_kadaluarsa', '<', now())
            ->count();

        // Ambil data untuk Tabel Notifikasi
        $listHampirKadaluarsa = $pegawai->berkas()
            ->whereNotNull('tanggal_kadaluarsa')
            ->where('tanggal_kadaluarsa', '>=', now())
            ->where('tanggal_kadaluarsa', '<=', now()->addDays(90))
            ->orderBy('tanggal_kadaluarsa', 'asc')
            ->get();
        
        // Kirim semua data yang dibutuhkan oleh view
        return view('dashboard.pegawai', compact(
            'pegawai',
            'totalBerkas',
            'berkasHampirKadaluarsa',
            'berkasSudahKadaluarsa',
            'listHampirKadaluarsa'
        ));
    }

    /**
     * Menampilkan halaman profil pegawai.
     */
    public function showProfile()
    {
        $pegawai = Auth::user()->pegawai()->with('jabatan', 'riwayatPendidikans', 'pelatihans')->firstOrFail();
        return view('pegawai.profile', compact('pegawai'));
    }

    /**
     * Memperbarui informasi profil pegawai.
     */
    public function updateProfile(Request $request)
    {
        // ... (kode ini sudah benar, tidak diubah)
        $pegawai = Auth::user()->pegawai;
        $user = Auth::user();

        $request->validate([
            'nama' => 'required|string|max:100',
            'nohp' => 'nullable|string|max:20',
            'tempatlahir' => 'nullable|string|max:100',
            'tanggal_lahir' => 'nullable|date',
            'alamat' => 'nullable|string',
        ]);

        $pegawai->update([
            'nama' => $request->nama,
            'nohp' => $request->nohp,
            'tempatlahir' => $request->tempatlahir,
            'tanggal_lahir' => $request->tanggal_lahir,
            'alamat' => $request->alamat,
        ]);
        
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
        // ... (kode ini sudah benar, tidak diubah)
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