@extends('layouts.dashboard')

@section('title', 'Tambah Data Pegawai')
@section('page-title', 'Tambah Data Pegawai')

@section('main-content')
<form action="{{ route('pegawai.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    {{-- KARTU INFORMASI PRIBADI & AKUN --}}
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title">Form Data Pegawai</h3>
        </div>
        <div class="card-body">
            <div class="row">
                {{-- Kolom Kiri --}}
                <div class="col-md-6">
                    <h5 class="font-weight-bold mb-3">Informasi Pribadi</h5>
                    <div class="form-group">
                        <label for="nama">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama" name="nama" value="{{ old('nama') }}" required>
                        @error('nama')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label for="nip">NIP <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('nip') is-invalid @enderror" id="nip" name="nip" value="{{ old('nip') }}" required>
                        @error('nip')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label for="jenis_kelamin">Jenis Kelamin <span class="text-danger">*</span></label>
                        <select class="form-control @error('jenis_kelamin') is-invalid @enderror" id="jenis_kelamin" name="jenis_kelamin" required>
                            <option value="">Pilih...</option>
                            <option value="L" {{ old('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="P" {{ old('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                        @error('jenis_kelamin')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label for="jabatan">Jabatan <span class="text-danger">*</span></label>
                        <select class="form-control @error('jabatan') is-invalid @enderror" id="jabatan" name="jabatan" required>
                            <option value="">Pilih...</option>
                            <option value="Dokter" {{ old('jabatan') == 'Dokter' ? 'selected' : '' }}>Dokter</option>
                            <option value="Perawat" {{ old('jabatan') == 'Perawat' ? 'selected' : '' }}>Perawat</option>
                            <option value="Bidan" {{ old('jabatan') == 'Bidan' ? 'selected' : '' }}>Bidan</option>
                            <option value="Apoteker" {{ old('jabatan') == 'Apoteker' ? 'selected' : '' }}>Apoteker</option>
                            <option value="IT" {{ old('jabatan') == 'IT' ? 'selected' : '' }}>IT</option>
                        </select>
                        @error('jabatan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label for="tempatlahir">Tempat Lahir</label>
                        <input type="text" class="form-control @error('tempatlahir') is-invalid @enderror" id="tempatlahir" name="tempatlahir" value="{{ old('tempatlahir') }}">
                    </div>
                    <div class="form-group">
                        <label for="tanggal_lahir">Tanggal Lahir</label>
                        <input type="date" class="form-control @error('tanggal_lahir') is-invalid @enderror" id="tanggal_lahir" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}">
                    </div>
                    <div class="form-group">
                        <label for="nohp">No. Handphone</label>
                        <input type="text" class="form-control @error('nohp') is-invalid @enderror" id="nohp" name="nohp" value="{{ old('nohp') }}">
                    </div>
                </div>

                {{-- Kolom Kanan --}}
                <div class="col-md-6">
                    <h5 class="font-weight-bold mb-3">Informasi Akun</h5>
                    <div class="form-group">
                        <label for="username">Username <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('username') is-invalid @enderror" id="username" name="username" value="{{ old('username') }}" required>
                        @error('username')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label for="email">Email <span class="text-danger">*</span></label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required>
                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label for="password">Password <span class="text-danger">*</span></label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required>
                        @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label for="password_confirmation">Konfirmasi Password <span class="text-danger">*</span></label>
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                    </div>
                    <div class="form-group">
                        <label for="mulaikerja">Mulai Bekerja</label>
                        <input type="date" class="form-control @error('mulaikerja') is-invalid @enderror" id="mulaikerja" name="mulaikerja" value="{{ old('mulaikerja') }}">
                    </div>
                     <div class="form-group">
                        <label for="catatan">Catatan</label>
                        <input type="text" class="form-control @error('catatan') is-invalid @enderror" id="catatan" name="catatan" value="{{ old('catatan') }}">
                    </div>
                     <div class="form-group">
                        <label for="alamat">Alamat</label>
                        <textarea class="form-control @error('alamat') is-invalid @enderror" id="alamat" name="alamat" rows="3">{{ old('alamat') }}</textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- KARTU RIWAYAT PENDIDIKAN & PELATIHAN --}}
    {{-- ... (Kode kartu riwayat dari sebelumnya tidak perlu diubah) ... --}}
    <div class="card card-outline card-info">
        <div class="card-header">
            <h3 class="card-title">Riwayat Pendidikan</h3>
            <div class="card-tools">
                <button type="button" id="add-pendidikan" class="btn btn-sm btn-success">
                    <i class="fas fa-plus"></i> Tambah Riwayat
                </button>
            </div>
        </div>
        <div class="card-body">
            <div id="pendidikan-wrapper">
                {{-- Item riwayat akan ditambahkan oleh JavaScript --}}
            </div>
        </div>
    </div>
    <div class="card card-outline card-info">
        <div class="card-header">
            <h3 class="card-title">Riwayat Pelatihan</h3>
            <div class="card-tools">
                <button type="button" id="add-pelatihan" class="btn btn-sm btn-success">
                    <i class="fas fa-plus"></i> Tambah Pelatihan
                </button>
            </div>
        </div>
        <div class="card-body">
            <div id="pelatihan-wrapper">
                {{-- Item pelatihan akan ditambahkan oleh JavaScript --}}
            </div>
        </div>
    </div>


    <div class="mt-4">
        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="{{ route('pegawai.index') }}" class="btn btn-secondary">Batal</a>
    </div>
</form>

<script>
    // ... (Script JavaScript dari sebelumnya tidak perlu diubah) ...
document.addEventListener('DOMContentLoaded', function () {
    function addItem(containerId, template) {
        const container = document.getElementById(containerId);
        const index = Date.now();
        const newItem = document.createElement('div');
        newItem.innerHTML = template.replace(/\[0\]/g, `[${index}]`);
        container.appendChild(newItem.firstElementChild);
    }

    document.addEventListener('click', function(e) {
        if (e.target && e.target.closest('.remove-item')) {
            e.target.closest('.row').remove();
        }
    });

    const pendidikanTemplate = `
    <div class="row align-items-end mb-3 pendidikan-item">
        <div class="col-md-5"><div class="form-group"><label>Nama Institusi</label><input type="text" name="pendidikan[0][nama_institusi]" class="form-control"></div></div>
        <div class="col-md-3"><div class="form-group"><label>Jurusan</label><input type="text" name="pendidikan[0][jurusan]" class="form-control"></div></div>
        <div class="col-md-2"><div class="form-group"><label>Tahun Lulus</label><input type="text" name="pendidikan[0][tahun_lulus]" class="form-control"></div></div>
        <div class="col-md-2"><button type="button" class="btn btn-danger remove-item">Hapus</button></div>
    </div>`;

    const pelatihanTemplate = `
    <div class="row align-items-end mb-3 pelatihan-item">
        <div class="col-md-5"><div class="form-group"><label>Nama Pelatihan</label><input type="text" name="pelatihan[0][nama_pelatihan]" class="form-control"></div></div>
        <div class="col-md-3"><div class="form-group"><label>Penyelenggara</label><input type="text" name="pelatihan[0][penyelenggara]" class="form-control"></div></div>
        <div class="col-md-2"><div class="form-group"><label>Tahun</label><input type="text" name="pelatihan[0][tahun]" class="form-control"></div></div>
        <div class="col-md-2"><button type="button" class="btn btn-danger remove-item">Hapus</button></div>
    </div>`;

    document.getElementById('add-pendidikan').addEventListener('click', () => addItem('pendidikan-wrapper', pendidikanTemplate));
    document.getElementById('add-pelatihan').addEventListener('click', () => addItem('pelatihan-wrapper', pelatihanTemplate));
});
</script>
@endsection