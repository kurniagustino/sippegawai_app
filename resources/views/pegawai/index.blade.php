@extends('layouts.dashboard')

@section('title', 'Data Pegawai')
@section('page-title', 'Data Pegawai')

@section('main-content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Manajemen Data Pegawai</h3>
        <div class="card-tools">
            <a href="{{ route('pegawai.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus mr-1"></i> Tambah Pegawai
            </a>
        </div>
    </div>
    <!-- /.card-header -->
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>Nama Pegawai</th>
                        <th>NIP</th>
                        <th>Jabatan</th>
                        <th>Email</th>
                        <th style="width: 180px;">Aksi</th> {{-- Lebarkan sedikit kolom aksi --}}
                    </tr>
                </thead>
                <tbody>
                    @forelse ($pegawai as $p)
                    <tr>
                        <td>{{ $p->nama }}</td>
                        <td>{{ $p->nip }}</td>
                        <td><span class="badge badge-secondary">{{ $p->jabatan->nama_jabatan ?? '-' }}</span></td>
                        <td>{{ $p->user->email ?? '-' }}</td>
                        <td>
    {{-- TOMBOL DETAIL BARU --}}
    <a href="{{ route('pegawai.show', $p->id) }}" class="btn btn-info btn-xs">Detail</a>
    <a href="{{ route('pegawai.edit', $p->id) }}" class="btn btn-warning btn-xs">Edit</a>
    <form action="{{ route('pegawai.destroy', $p->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger btn-xs">Hapus</button>
    </form>
</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center">Tidak ada data pegawai.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <!-- /.card-body -->
    <div class="card-footer clearfix">
        {{ $pegawai->links() }}
    </div>
</div>
<!-- /.card -->
@endsection
