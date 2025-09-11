@extends('layouts.dashboard')

@section('title', 'Berkas Saya')
@section('page-title', 'Berkas Saya')

@section('main-content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Daftar Berkas Saya</h3>
        <div class="card-tools">
            <a href="{{ route('pegawai.berkas.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-upload"></i> Unggah Berkas
            </a>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-striped table-hover m-0">
                <thead>
                    <tr>
                        <th style="width: 10px">#</th>
                        <th>Nama Berkas</th>
                        <th>Tanggal Unggah</th>
                        <th>Tanggal Kadaluarsa</th>
                        <th style="width: 150px">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($berkas as $item)
                    <tr>
                        <td>{{ $berkas->firstItem() + $loop->index }}</td>
                        <td>{{ $item->nama_berkas }}</td>
                        <td>{{ \Carbon\Carbon::parse($item->tanggal_upload)->isoFormat('D MMMM YYYY') }}</td>
                        <td>{{ $item->tanggal_kadaluarsa ?
                            \Carbon\Carbon::parse($item->tanggal_kadaluarsa)->isoFormat('D MMMM YYYY') : '-' }}</td>
                        <td class="d-flex align-items-center gap-1">
                            {{-- Ubah btn-sm menjadi btn-xs --}}
                            <a href="#" class="btn btn-info btn-xs view-file-btn"
                                data-file-url="{{ Storage::url($item->file_path) }}">
                                <i class="fas fa-eye"></i> Lihat
                            </a>

                            <form action="{{ route('pegawai.berkas.destroy', $item->id) }}" method="POST"
                                class="delete-form">
                                @csrf
                                @method('DELETE')
                                {{-- Ubah btn-sm menjadi btn-xs --}}
                                <button type="submit" class="btn btn-danger btn-xs">
                                    <i class="fas fa-trash"></i> Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center">Tidak ada berkas yang diunggah.</td>
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

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        
        function isMobileDevice() {
            return /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
        }

        document.querySelectorAll('.view-file-btn').forEach(button => {
            button.addEventListener('click', function (event) {
                event.preventDefault(); 
                
                const fileUrl = this.getAttribute('data-file-url');
                const fileExtension = fileUrl.split('.').pop().toLowerCase();
                let contentHtml = '';

                if (fileExtension === 'pdf') {
                    if (isMobileDevice()) {
                        contentHtml = `
                            <div class="text-center p-4">
                                <i class="fas fa-file-pdf fa-3x text-muted mb-3"></i>
                                <p>Pratinjau PDF tidak didukung di mobile.</p>
                                <a href="${fileUrl}" target="_blank" class="btn btn-primary mt-2">
                                    <i class="fas fa-external-link-alt"></i> Buka PDF di Tab Baru
                                </a>
                            </div>`;
                    } else {
                        contentHtml = `<iframe src="${fileUrl}" width="100%" height="600px" style="border: none;"></iframe>`;
                    }
                } else if (['jpg', 'jpeg', 'png', 'gif', 'webp'].includes(fileExtension)) {
                    contentHtml = `<img src="${fileUrl}" style="max-width: 100%; height: auto;">`;
                } else {
                    contentHtml = `
                        <div class="text-center p-4">
                            <i class="fas fa-file-alt fa-3x text-muted mb-3"></i>
                            <p>Pratinjau tidak tersedia untuk tipe berkas ini.</p>
                            <a href="${fileUrl}" target="_blank" class="btn btn-primary mt-2">
                                <i class="fas fa-download"></i> Unduh Berkas
                            </a>
                        </div>`;
                }

                Swal.fire({
                    title: 'Pratinjau Berkas',
                    html: contentHtml,
                    width: isMobileDevice() ? '90%' : '80%',
                    showCloseButton: true,
                    showConfirmButton: false,
                });
            });
        });
    });
</script>
@endpush