@extends('layouts.dashboard')

@section('title', 'Dashboard Admin')
@section('page-title', 'Dashboard')

@section('main-content')
{{-- Stat Box / Small Box --}}
<div class="row">
    <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3>{{ $totalPegawai }}</h3>
                <p>Total Pegawai</p>
            </div>
            <div class="icon">
                <i class="fas fa-users"></i>
            </div>
            <a href="{{ route('pegawai.index') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box" style="background-color: #e83e8c; color: white;">
            <div class="inner">
                <h3>{{ $berkasKadaluarsa }}</h3>
                <p>Total Berkas Kadaluarsa</p>
            </div>
            <div class="icon">
                <i class="fas fa-folder-minus"></i>
            </div>
            <a href="{{ route('berkas.index') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3>{{ $berkasHampirKadaluarsa }}</h3>
                <p>Total Berkas Hampir Kadaluarsa</p>
            </div>
            <div class="icon">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <a href="{{ route('berkas.index') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>{{ $totalUserAktif }}</h3>
                <p>Total User Aktif</p>
            </div>
            <div class="icon">
                <i class="fas fa-user-plus"></i>
            </div>
            <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
</div>

{{-- Tabel Berkas Hampir Kadaluarsa --}}
<div class="card card-warning card-outline">
    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-exclamation-triangle mr-1"></i>
            Berkas Mendekati Kadaluarsa (Dalam 90 Hari)
        </h3>
        <div class="card-tools">
            <a href="#" class="btn btn-sm btn-danger">
                <i class="fas fa-file-pdf mr-1"></i> Cetak PDF
            </a>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th style="width: 10px">No</th>
                        <th>Nama Pegawai</th>
                        <th>Nama Berkas</th>
                        <th>Tanggal Kadaluarsa</th>
                        <th style="width: 150px">Sisa Waktu</th>
                        <th style="width: 40px">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($listBerkasHampirKadaluarsa as $item)
                        <tr>
                            <td>{{ $loop->iteration }}.</td>
                            <td>{{ $item->pegawai->nama }}</td>
                            <td>{{ $item->nama_berkas }}</td>
                            <td>{{ \Carbon\Carbon::parse($item->tanggal_kadaluarsa)->isoFormat('D MMMM YYYY') }}</td>
                            <td>
                                <span class="badge badge-warning">{{ \Carbon\Carbon::parse($item->tanggal_kadaluarsa)->diffForHumans(['short' => true]) }} lagi</span>
                            </td>
                            <td>
                                <a href="{{ route('berkas.show', $item->id) }}" class="btn btn-xs btn-info">Lihat</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">Tidak ada berkas yang mendekati kadaluarsa.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>


{{-- Tabel Berkas Sudah Kadaluarsa --}}
<div class="card card-danger card-outline">
    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-times-circle mr-1"></i>
            Berkas Sudah Kadaluarsa
        </h3>
        <div class="card-tools">
            <a href="#" class="btn btn-sm btn-danger">
                <i class="fas fa-file-pdf mr-1"></i> Cetak PDF
            </a>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th style="width: 10px">No</th>
                        <th>Nama Pegawai</th>
                        <th>Nama Berkas</th>
                        <th>Tanggal Kadaluarsa</th>
                        <th style="width: 150px">Terlewat</th>
                        <th style="width: 40px">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                     @forelse ($listBerkasKadaluarsa as $item)
                        <tr>
                            <td>{{ $loop->iteration }}.</td>
                            <td>{{ $item->pegawai->nama }}</td>
                            <td>{{ $item->nama_berkas }}</td>
                            <td>{{ \Carbon\Carbon::parse($item->tanggal_kadaluarsa)->isoFormat('D MMMM YYYY') }}</td>
                            <td>
                                <span class="badge badge-danger">{{ \Carbon\Carbon::parse($item->tanggal_kadaluarsa)->diffForHumans(['short' => true]) }} lalu</span>
                            </td>
                            <td>
                                <a href="{{ route('berkas.show', $item->id) }}" class="btn btn-xs btn-info">Lihat</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">Tidak ada berkas yang sudah kadaluarsa.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection