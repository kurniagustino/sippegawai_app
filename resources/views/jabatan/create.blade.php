        @extends('layouts.dashboard')
        @section('title', 'Tambah Jabatan')
        @section('page-title', 'Tambah Jabatan')
        @section('main-content')
        <div class="card card-primary">
            <div class="card-header"><h3 class="card-title">Form Tambah Jabatan</h3></div>
            <form action="{{ route('jabatan.store') }}" method="POST">
                @csrf
                <div class="card-body">
                    <div class="form-group">
                        <label for="nama_jabatan">Nama Jabatan <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('nama_jabatan') is-invalid @enderror" id="nama_jabatan" name="nama_jabatan" value="{{ old('nama_jabatan') }}" required>
                        @error('nama_jabatan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <a href="{{ route('jabatan.index') }}" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
        @endsection
        
