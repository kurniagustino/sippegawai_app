<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Pegawai;
use App\Models\Berkas;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Menampilkan dashboard berdasarkan role pengguna yang sedang login.
     * Ini adalah method tunggal yang akan dipanggil oleh rute /dashboard.
     */
    public function index()
    {
        $user = auth()->user();

        // Cek role pengguna
        if ($user->role === 'admin') {
            // Logika untuk dashboard admin
            $totalPegawai = Pegawai::count();
            $totalUserAktif = User::count();
            $berkasKadaluarsa = Berkas::where('tanggal_kadaluarsa', '<', now())->count();
            $berkasHampirKadaluarsa = Berkas::where('tanggal_kadaluarsa', '>', now())
                                             ->where('tanggal_kadaluarsa', '<=', now()->addDays(90))
                                             ->count();

            $listBerkasKadaluarsa = Berkas::with('pegawai')
                                         ->where('tanggal_kadaluarsa', '<', now())
                                         ->get();

            $listBerkasHampirKadaluarsa = Berkas::with('pegawai')
                                                 ->where('tanggal_kadaluarsa', '>', now())
                                                 ->where('tanggal_kadaluarsa', '<=', now()->addDays(90))
                                                 ->get();

            return view('dashboard.admin', compact(
                'totalPegawai',
                'totalUserAktif',
                'berkasKadaluarsa',
                'berkasHampirKadaluarsa',
                'listBerkasKadaluarsa',
                'listBerkasHampirKadaluarsa'
            ));
        }

        // Jika bukan admin, asumsikan sebagai pegawai
        // Anda bisa menambahkan 'else if ($user->role === 'pegawai')'
        // untuk memastikan, tapi ini sudah cukup.
        $pegawai = Pegawai::where('idUser', $user->id)->first();
        return view('dashboard.pegawai', compact('pegawai'));
    }
}