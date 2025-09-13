@extends('layouts.dashboard')

@section('title', 'Dashboard Pegawai')
@section('page-title', 'Dashboard')

@section('main-content')
{{-- Baris 1: Welcome & Profil Singkat --}}
<div class="row">
    <div class="col-lg-8">
        {{-- Kartu Selamat Datang --}}
        <div class="card card-primary card-outline">
            <div class="card-body">
                <h4 class="font-weight-bold">Halo, {{ $pegawai->nama }}!</h4>
                <p class="text-muted">Selamat datang kembali di Sistem Informasi Pegawai. Berikut adalah ringkasan
                    informasi dan status berkas Anda.</p>
                <hr>
                <div class="row">
                    <div class="col-md-6">
                        <strong><i class="fas fa-id-card mr-1"></i> NIP</strong>
                        <p class="text-muted">{{ $pegawai->nip }}</p>
                    </div>
                    <div class="col-md-6">
                        <strong><i class="fas fa-briefcase mr-1"></i> Jabatan</strong>
                        <p class="text-muted">{{ $pegawai->jabatan->nama_jabatan ?? 'Belum diatur' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        {{-- Kartu Profil & Aksi Cepat --}}
        <div class="card">
            <div class="card-body text-center">
                @if($pegawai->foto)
                {{-- Jika ada foto, tampilkan dari storage --}}
                <img class="profile-user-img img-fluid img-circle" src="{{ Storage::url($pegawai->foto) }}"
                    alt="Foto profil {{ $pegawai->nama }}" style="width: 128px; height: 128px; object-fit: cover;">
                @else
                {{-- Jika tidak ada foto, tampilkan avatar default --}}
                <img class="profile-user-img img-fluid img-circle"
                    src="https://ui-avatars.com/api/?name={{ urlencode($pegawai->nama) }}&background=007bff&color=fff&size=128"
                    alt="User profile picture">
                @endif
                <h5 class="mt-3">{{ $pegawai->nama }}</h5>
                <p class="text-muted">{{ $pegawai->user->email ?? '-' }}</p>
                <a href="{{ route('pegawai.profile.show') }}" class="btn btn-primary btn-block"><b>Lihat Profil
                        Saya</b></a>
            </div>
        </div>
    </div>
</div>

{{-- Baris 2: Stat Box / Small Box --}}
<div class="row">
    <div class="col-lg-4 col-md-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3>{{ $totalBerkas }}</h3>
                <p>Total Berkas Saya</p>
            </div>
            <div class="icon"><i class="fas fa-folder"></i></div>
            <a href="{{ route('pegawai.berkas.index') }}" class="small-box-footer">Lihat Detail <i
                    class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <div class="col-lg-4 col-md-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3>{{ $berkasHampirKadaluarsa }}</h3>
                <p>Berkas Hampir Kadaluarsa</p>
            </div>
            <div class="icon"><i class="fas fa-exclamation-triangle"></i></div>
            <a href="{{ route('pegawai.berkas.index') }}" class="small-box-footer">Lihat Detail <i
                    class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <div class="col-lg-4 col-md-6">
        <div class="small-box bg-danger">
            <div class="inner">
                <h3>{{ $berkasSudahKadaluarsa }}</h3>
                <p>Berkas Sudah Kadaluarsa</p>
            </div>
            <div class="icon"><i class="fas fa-times-circle"></i></div>
            <a href="{{ route('pegawai.berkas.index') }}" class="small-box-footer">Lihat Detail <i
                    class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
</div>

{{-- Baris 3: Tabel Notifikasi Berkas Mendekati Kadaluarsa --}}
<div class="card card-warning card-outline">
    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-bell mr-1"></i>
            Notifikasi: Berkas Mendekati Kadaluarsa (Dalam 90 Hari)
        </h3>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th style="width: 10px">No</th>
                        <th>Nama Berkas</th>
                        <th>Tanggal Kadaluarsa</th>
                        <th style="width: 150px">Sisa Waktu</th>
                        <th style="width: 120px">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($listHampirKadaluarsa as $item)
                    <tr>
                        <td>{{ $loop->iteration }}.</td>
                        <td>{{ $item->nama_berkas }}</td>
                        <td>{{ \Carbon\Carbon::parse($item->tanggal_kadaluarsa)->isoFormat('D MMMM YYYY') }}</td>
                        <td>
                            <span class="badge badge-warning">
                                {{ \Carbon\Carbon::now()->diffInDays($item->tanggal_kadaluarsa) }} hari lagi
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('pegawai.berkas.index') }}" class="btn btn-xs btn-primary">Perbarui
                                Berkas</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center">
                            <i class="fas fa-check-circle text-success"></i> Semua berkas Anda aman.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection