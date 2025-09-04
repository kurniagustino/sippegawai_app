@extends('layouts.dashboard')

@section('title', 'Detail Berkas')
@section('page-title', 'Detail Berkas')

@section('main-content')
<div class="row">
    <div class="col-md-4">
        {{-- Informasi Berkas --}}
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Informasi Berkas</h3>
            </div>
            <div class="card-body">
                <strong><i class="fas fa-book mr-1"></i> Nama Berkas</strong>
                <p class="text-muted">{{ $berka->nama_berkas }}</p>
                <hr>
                <strong><i class="fas fa-user mr-1"></i> Milik Pegawai</strong>
                <p class="text-muted">{{ $berka->pegawai->nama }} (NIP: {{ $berka->pegawai->nip }})</p>
                <hr>
                <strong><i class="fas fa-calendar-upload mr-1"></i> Tanggal Upload</strong>
                <p class="text-muted">{{ \Carbon\Carbon::parse($berka->tanggal_upload)->isoFormat('D MMMM YYYY') }}</p>
                <hr>
                <strong><i class="fas fa-calendar-times mr-1"></i> Tanggal Kadaluarsa</strong>
                <p class="text-muted">
                    @if($berka->tanggal_kadaluarsa)
                        {{ \Carbon\Carbon::parse($berka->tanggal_kadaluarsa)->isoFormat('D MMMM YYYY') }}
                    @else
                        Tidak Ada
                    @endif
                </p>
                <hr>
                <a href="{{ route('berkas.index') }}" class="btn btn-secondary btn-block mt-3">Kembali ke Daftar</a>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        {{-- Preview File --}}
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title">Preview File</h3>
            </div>
            <div class="card-body p-0" style="min-height: 500px;">
                @php
                    // Cek ekstensi file
                    $extension = pathinfo(storage_path('app/' . $berka->file_path), PATHINFO_EXTENSION);
                @endphp

                @if (in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'gif']))
                    {{-- Jika gambar, tampilkan dengan tag <img> --}}
                    <img src="{{ Storage::url($berka->file_path) }}" class="img-fluid" alt="Preview Gambar">
                @elseif (strtolower($extension) == 'pdf')
                    {{-- Jika PDF, tampilkan dengan tag <embed> --}}
                    <embed src="{{ Storage::url($berka->file_path) }}" type="application/pdf" width="100%" height="600px" />
                @else
                    {{-- Jika file lain, beri pesan --}}
                    <div class="text-center p-5">
                        <i class="fas fa-file-alt fa-3x text-muted"></i>
                        <p class="mt-3 text-muted">Preview tidak tersedia untuk tipe file ini.</p>
                        <a href="{{ Storage::url($berka->file_path) }}" class="btn btn-primary mt-3" download>
                            <i class="fas fa-download mr-1"></i> Download File
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection