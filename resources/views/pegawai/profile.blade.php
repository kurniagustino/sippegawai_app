@extends('layouts.dashboard')

@section('title', 'Detail Pegawai')
@section('page-title', 'Detail Pegawai')

@section('main-content')
<div class="row">
    {{-- Kolom Kiri: Informasi Utama & Foto --}}
    <div class="col-md-4">
        <div class="card card-primary card-outline">
            <div class="card-body box-profile">
                <div class="text-center">
                    @if($pegawai->foto)
                        <img class="profile-user-img img-fluid img-circle" src="{{ Storage::url($pegawai->foto) }}"
                             alt="Foto profil {{ $pegawai->nama }}" style="width: 128px; height: 128px; object-fit: cover;">
                    @else
                        <img class="profile-user-img img-fluid img-circle"
                             src="https://ui-avatars.com/api/?name={{ urlencode($pegawai->nama) }}&background=007bff&color=fff&size=128"
                             alt="User profile picture">
                    @endif
                </div>
                <h3 class="profile-username text-center">{{ $pegawai->nama }}</h3>
                <p class="text-muted text-center">{{ $pegawai->jabatan->nama_jabatan ?? '-' }}</p>

                @if (auth()->check() && auth()->user()->id === $pegawai->user->id)
                    <a href="{{ route('pegawai.profile.edit') }}" class="btn btn-warning btn-block mb-2"><b><i class="fas fa-pencil-alt mr-1"></i> Edit Detail Profil</b></a>
                    <button type="button" class="btn btn-primary btn-block" data-toggle="modal" data-target="#uploadFotoModal">
                        <b><i class="fas fa-camera mr-1"></i> Ganti Foto Profil</b>
                    </button>
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

{{-- ========================================================== --}}
{{--        MODAL SEDERHANA UNTUK UPLOAD FOTO PROFIL            --}}
{{-- ========================================================== --}}
<div class="modal fade" id="uploadFotoModal" tabindex="-1" role="dialog" aria-labelledby="uploadFotoModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="uploadFotoModalLabel">Unggah Foto Profil Baru</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('pegawai.profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <p class="text-center text-muted">Pilih foto baru untuk diunggah (JPG, PNG, maks 2MB).</p>
                    <div class="text-center mb-3">
                         <img class="img-fluid img-circle" id="modal-foto-preview"
                             src="{{ $pegawai->foto ? Storage::url($pegawai->foto) : 'https://ui-avatars.com/api/?name='.urlencode($pegawai->nama).'&background=6c757d&color=fff&size=128' }}"
                             alt="Foto profil preview" style="width: 128px; height: 128px; object-fit: cover;">
                    </div>
                    <div class="form-group">
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="modal-foto-input" name="foto" required accept="image/png, image/jpeg">
                            <label class="custom-file-label" for="modal-foto-input">Pilih file...</label>
                        </div>
                         @error('foto')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Foto</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
{{-- Menambahkan SweetAlert2 untuk notifikasi --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    // ==========================================================
    // SCRIPT BERSIH HANYA UNTUK MODAL SEDERHANA + NOTIFIKASI UKURAN
    // ==========================================================
    const fotoInput = document.getElementById('modal-foto-input');
    const fotoPreview = document.getElementById('modal-foto-preview');
    const fotoLabel = document.querySelector('.custom-file-label[for="modal-foto-input"]');

    // Pastikan semua elemen ada sebelum menambahkan event listener
    if (fotoInput && fotoPreview && fotoLabel) {
        fotoInput.addEventListener('change', function(event) {
            const file = event.target.files[0];
            const maxFileSize = 2 * 1024 * 1024; // 2MB

            if (file) {
                // Pengecekan ukuran file
                if (file.size > maxFileSize) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Ukuran File Terlalu Besar',
                        text: 'Ukuran file maksimal adalah 2 MB. Silakan pilih file lain.',
                    });
                    // Reset input file
                    fotoInput.value = "";
                    fotoLabel.textContent = 'Pilih file...';
                    fotoPreview.src = "{{ $pegawai->foto ? Storage::url('foto-pegawai/' . $pegawai->foto) : 'https://ui-avatars.com/api/?name='.urlencode($pegawai->nama).'&background=6c757d&color=fff&size=128' }}"; // Kembalikan ke foto awal
                    return;
                }

                // Tampilkan nama file di label
                fotoLabel.textContent = file.name;

                // Tampilkan preview gambar
                const reader = new FileReader();
                reader.onload = function(e) {
                    fotoPreview.src = e.target.result;
                }
                reader.readAsDataURL(file);
            }
        });
    }
});
</script>
@endpush
