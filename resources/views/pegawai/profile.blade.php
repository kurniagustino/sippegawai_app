@extends('layouts.dashboard')

@section('title', 'Detail Pegawai')
@section('page-title', 'Detail Pegawai')

@section('main-content')
<div class="row">
    {{-- Kolom Kiri: Informasi Utama --}}
    <div class="col-md-4">
        <div class="card card-primary card-outline">
            <div class="card-body box-profile">
                <div class="text-center">
                    <img class="profile-user-img img-fluid img-circle"
                         src="https://ui-avatars.com/api/?name={{ urlencode($pegawai->nama) }}&background=007bff&color=fff&size=128"
                         alt="User profile picture">
                </div>
                <h3 class="profile-username text-center">{{ $pegawai->nama }}</h3>
                <p class="text-muted text-center">{{ $pegawai->jabatan->nama_jabatan ?? '-' }}</p>

                <ul class="list-group list-group-unbordered mb-3">
                    <li class="list-group-item">
                        <b>NIP</b> <a class="float-right">{{ $pegawai->nip }}</a>
                    </li>
                    <li class="list-group-item">
                        <b>Email</b> <a class="float-right">{{ $pegawai->user->email ?? '-' }}</a>
                    </li>
                    <li class="list-group-item">
                        <b>Username</b> <a class="float-right">{{ $pegawai->user->username ?? '-' }}</a>
                    </li>
                </ul>
                {{-- Kondisi untuk tombol Kembali --}}
                @if (auth()->check() && auth()->user()->role === 'admin')
                    <a href="{{ route('pegawai.index') }}" class="btn btn-secondary"><b>Kembali</b></a>
                @endif
                {{-- Logika baru untuk tombol Edit --}}
                @if (auth()->check() && auth()->user()->id === $pegawai->user->id)
                    {{-- Perbaikan: Tombol ini untuk pegawai biasa, mengarah ke rute profil mereka sendiri --}}
                    <a href="{{ route('pegawai.profile.edit') }}" class="btn btn-warning"><b>Edit Profil Saya</b></a>
                @elseif (auth()->check() && auth()->user()->role === 'admin')
                    {{-- Tombol ini hanya untuk admin, mengarah ke rute edit admin --}}
                    <a href="{{ route('pegawai.edit', $pegawai->id) }}" class="btn btn-warning"><b>Edit Pegawai</b></a>
                @endif
            </div>
        </div>
    </div>

    {{-- Kolom Kanan: Detail & Riwayat --}}
    <div class="col-md-8">
        <div class="card">
            <div class="card-header p-2">
                <ul class="nav nav-pills">
                    <li class="nav-item"><a class="nav-link active" href="#detail" data-toggle="tab">Detail Pribadi</a></li>
                    <li class="nav-item"><a class="nav-link" href="#pendidikan" data-toggle="tab">Riwayat Pendidikan</a></li>
                    <li class="nav-item"><a class="nav-link" href="#pelatihan" data-toggle="tab">Riwayat Pelatihan</a></li>
                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content">
                    {{-- Tab Detail Pribadi --}}
                    <div class="active tab-pane" id="detail">
                        <strong><i class="fas fa-venus-mars mr-1"></i> Jenis Kelamin</strong>
                        <p class="text-muted">{{ $pegawai->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</p>
                        <hr>
                        <strong><i class="fas fa-map-marker-alt mr-1"></i> Tempat & Tanggal Lahir</strong>
                        <p class="text-muted">{{ $pegawai->tempatlahir ?? '-' }}, {{ $pegawai->tanggal_lahir ? \Carbon\Carbon::parse($pegawai->tanggal_lahir)->isoFormat('D MMMM YYYY') : '-' }}</p>
                        <hr>
                        <strong><i class="fas fa-phone mr-1"></i> No. Handphone</strong>
                        <p class="text-muted">{{ $pegawai->nohp ?? '-' }}</p>
                        <hr>
                        <strong><i class="fas fa-calendar-alt mr-1"></i> Mulai Bekerja</strong>
                        <p class="text-muted">{{ $pegawai->mulaikerja ? \Carbon\Carbon::parse($pegawai->mulaikerja)->isoFormat('D MMMM YYYY') : '-' }}</p>
                        <hr>
                        <strong><i class="far fa-file-alt mr-1"></i> Alamat</strong>
                        <p class="text-muted">{{ $pegawai->alamat ?? '-' }}</p>
                        <hr>
                        <strong><i class="far fa-sticky-note mr-1"></i> Catatan</strong>
                        <p class="text-muted">{{ $pegawai->catatan ?? '-' }}</p>
                    </div>

                    {{-- Tab Riwayat Pendidikan --}}
                    <div class="tab-pane" id="pendidikan">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Nama Institusi</th>
                                    <th>Jurusan</th>
                                    <th>Tahun Lulus</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($pegawai->riwayatPendidikans as $item)
                                <tr>
                                    <td>{{ $item->nama_institusi }}</td>
                                    <td>{{ $item->jurusan ?? '-' }}</td>
                                    <td>{{ $item->tahun_lulus }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="text-center">Tidak ada data riwayat pendidikan.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Tab Riwayat Pelatihan --}}
                    <div class="tab-pane" id="pelatihan">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Nama Pelatihan</th>
                                    <th>Penyelenggara</th>
                                    <th>Tahun</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($pegawai->pelatihans as $item)
                                <tr>
                                    <td>{{ $item->nama_pelatihan }}</td>
                                    <td>{{ $item->penyelenggara ?? '-' }}</td>
                                    <td>{{ $item->tahun }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="text-center">Tidak ada data riwayat pelatihan.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
