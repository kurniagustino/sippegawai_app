    @extends('layouts.dashboard')

    @section('title', 'Berkas Pegawai')
    @section('page-title', 'Berkas Pegawai')

    @section('main-content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Manajemen Berkas Pegawai</h3>
            <div class="card-tools">
                <a href="{{ route('berkas.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus mr-1"></i> Upload Berkas Baru
                </a>
            </div>
        </div>
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
                            <th>Nama Berkas</th>
                            <th>Nama Pegawai</th>
                            <th>Tgl. Upload</th>
                            <th>Tgl. Kadaluarsa</th>
                            <th style="width: 150px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($berkas as $file)
                        <tr>
                            <td>{{ $file->nama_berkas }}</td>
                            <td>{{ $file->pegawai->nama ?? 'N/A' }}</td>
                            <td>{{ \Carbon\Carbon::parse($file->tanggal_upload)->format('d M Y') }}</td>
                            <td>
                                @if($file->tanggal_kadaluarsa)
                                    {{ \Carbon\Carbon::parse($file->tanggal_kadaluarsa)->format('d M Y') }}
                                    @if(\Carbon\Carbon::parse($file->tanggal_kadaluarsa)->isPast())
                                        <span class="badge badge-danger">Kadaluarsa</span>
                                    @endif
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('berkas.show', $file->id) }}" class="btn btn-info btn-xs">Lihat</a>
                                 <a href="{{ route('berkas.edit', $file->id) }}" class="btn btn-warning btn-xs">Edit</a>
                                <form action="{{ route('berkas.destroy', $file->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus berkas ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-xs">Hapus</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center">Belum ada berkas yang di-upload.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer clearfix">
            {{ $berkas->links() }}
        </div>
    </div>
    @endsection
    
