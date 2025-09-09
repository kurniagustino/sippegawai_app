@extends('layouts.dashboard')

@section('title', 'Edit Profil Saya')
@section('page-title', 'Edit Profil Saya')

@section('main-content')
<form action="{{ route('pegawai.profile.update') }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    {{-- KARTU INFORMASI PRIBADI & AKUN --}}
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title">Form Edit: {{ $pegawai->nama }}</h3>
        </div>
        <div class="card-body">
            <div class="row">
                {{-- Kolom Kiri --}}
                <div class="col-md-6">
                    <h5 class="font-weight-bold mb-3">Informasi Pribadi</h5>
                    <div class="form-group">
                        <label for="nama">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="nama" name="nama"
                            value="{{ old('nama', $pegawai->nama) }}" required>
                    </div>
                    <div class="form-group">
                        <label for="nip">NIP <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="nip" name="nip"
                            value="{{ old('nip', $pegawai->nip) }}" required>
                    </div>
                    <div class="form-group">
                        <label for="jenis_kelamin">Jenis Kelamin <span class="text-danger">*</span></label>
                        <select class="form-control" id="jenis_kelamin" name="jenis_kelamin" required>
                            <option value="L" {{ old('jenis_kelamin', $pegawai->jenis_kelamin) == 'L' ? 'selected' : ''
                                }}>Laki-laki</option>
                            <option value="P" {{ old('jenis_kelamin', $pegawai->jenis_kelamin) == 'P' ? 'selected' : ''
                                }}>Perempuan</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="jabatan_id">Jabatan <span class="text-danger">*</span></label>
                        <select class="form-control @error('jabatan_id') is-invalid @enderror" id="jabatan_id"
                            name="jabatan_id" required>
                            <option value="">Pilih...</option>
                            @foreach($jabatans as $jabatan)
                            <option value="{{ $jabatan->id }}" {{ old('jabatan_id', $pegawai->jabatan_id) ==
                                $jabatan->id ? 'selected' : '' }}>
                                {{ $jabatan->nama_jabatan }}
                            </option>
                            @endforeach
                        </select>
                        @error('jabatan_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label for="tempatlahir">Tempat Lahir</label>
                        <input type="text" class="form-control" id="tempatlahir" name="tempatlahir"
                            value="{{ old('tempatlahir', $pegawai->tempatlahir) }}">
                    </div>
                    <div class="form-group">
                        <label for="tanggal_lahir">Tanggal Lahir</label>
                        <input type="date" class="form-control" id="tanggal_lahir" name="tanggal_lahir"
                            value="{{ old('tanggal_lahir', $pegawai->tanggal_lahir) }}">
                    </div>
                    <div class="form-group">
                        <label for="nohp">No. Handphone</label>
                        <input type="text" class="form-control" id="nohp" name="nohp"
                            value="{{ old('nohp', $pegawai->nohp) }}">
                    </div>
                </div>
                {{-- Kolom Kanan --}}
                <div class="col-md-6">
                    <h5 class="font-weight-bold mb-3">Informasi Akun</h5>
                    <div class="form-group">
                        <label for="username">Username <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="username" name="username"
                            value="{{ old('username', $pegawai->user->username) }}" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="email" name="email"
                            value="{{ old('email', $pegawai->user->email) }}">
                    </div>
                    <div class="form-group">
                        <label for="password">Password Baru</label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror"
                            id="password" name="password">
                        <small class="form-text text-muted">Kosongkan jika tidak ingin mengubah password.</small>
                        @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label for="password_confirmation">Konfirmasi Password Baru</label>
                        <input type="password" class="form-control" id="password_confirmation"
                            name="password_confirmation">
                    </div>
                    <div class="form-group">
                        <label for="mulaikerja">Mulai Bekerja</label>
                        <input type="date" class="form-control" id="mulaikerja" name="mulaikerja"
                            value="{{ old('mulaikerja', $pegawai->mulaikerja) }}">
                    </div>
                    <div class="form-group">
                        <label for="catatan">Catatan</label>
                        <input type="text" class="form-control" id="catatan" name="catatan"
                            value="{{ old('catatan', $pegawai->catatan) }}">
                    </div>
                    <div class="form-group">
                        <label for="alamat">Alamat</label>
                        <textarea class="form-control" id="alamat" name="alamat"
                            rows="3">{{ old('alamat', $pegawai->alamat) }}</textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- KARTU RIWAYAT PENDIDIKAN & PELATIHAN --}}
    <div class="card card-outline card-info">
        <div class="card-header">
            <h3 class="card-title">Riwayat Pendidikan</h3>
            <div class="card-tools">
                <button type="button" id="add-pendidikan" class="btn btn-sm btn-success"><i class="fas fa-plus"></i>
                    Tambah</button>
            </div>
        </div>
        <div class="card-body">
            <div id="pendidikan-wrapper">
                @foreach($pegawai->riwayatPendidikans as $index => $pendidikan)
                <div class="row align-items-end mb-3 pendidikan-item">
                    <div class="col-md-5">
                        <div class="form-group"><label>Nama Institusi</label><input type="text"
                                name="pendidikan[{{ $index }}][nama_institusi]" class="form-control"
                                value="{{ $pendidikan->nama_institusi }}"></div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group"><label>Jurusan</label><input type="text"
                                name="pendidikan[{{ $index }}][jurusan]" class="form-control"
                                value="{{ $pendidikan->jurusan }}"></div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group"><label>Tahun Lulus</label><input type="text"
                                name="pendidikan[{{ $index }}][tahun_lulus]" class="form-control"
                                value="{{ $pendidikan->tahun_lulus }}"></div>
                    </div>
                    <div class="col-md-2"><button type="button" class="btn btn-danger remove-item">Hapus</button></div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    <div class="card card-outline card-info">
        <div class="card-header">
            <h3 class="card-title">Riwayat Pelatihan</h3>
            <div class="card-tools">
                <button type="button" id="add-pelatihan" class="btn btn-sm btn-success"><i class="fas fa-plus"></i>
                    Tambah</button>
            </div>
        </div>
        <div class="card-body">
            <div id="pelatihan-wrapper">
                @foreach($pegawai->pelatihans as $index => $pelatihan)
                <div class="row align-items-end mb-3 pelatihan-item">
                    <div class="col-md-5">
                        <div class="form-group"><label>Nama Pelatihan</label><input type="text"
                                name="pelatihan[{{ $index }}][nama_pelatihan]" class="form-control"
                                value="{{ $pelatihan->nama_pelatihan }}"></div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group"><label>Penyelenggara</label><input type="text"
                                name="pelatihan[{{ $index }}][penyelenggara]" class="form-control"
                                value="{{ $pelatihan->penyelenggara }}"></div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group"><label>Tahun</label><input type="text"
                                name="pelatihan[{{ $index }}][tahun]" class="form-control"
                                value="{{ $pelatihan->tahun }}"></div>
                    </div>
                    <div class="col-md-2"><button type="button" class="btn btn-danger remove-item">Hapus</button></div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="mt-4">
        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        <a href="{{ route('pegawai.profile.show') }}" class="btn btn-secondary">Batal</a>
    </div>
</form>

<script>
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