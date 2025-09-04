@extends('layouts.dashboard')

@section('title', 'Edit Informasi Berkas')
@section('page-title', 'Edit Informasi Berkas')

@section('main-content')
<div class="card card-primary">
    <div class="card-header">
        <h3 class="card-title">Form Edit Berkas</h3>
    </div>
    <form action="{{ route('berkas.update', $berka->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="card-body">
            <div class="row">
                <div class="col-md-7">
                    {{-- Kolom Kiri untuk Form Input --}}
                    <div class="form-group">
                        <label for="pegawai_id">Pegawai <span class="text-danger">*</span></label>
                        <select class="form-control @error('pegawai_id') is-invalid @enderror" id="pegawai_id" name="pegawai_id" required>
                            @foreach($pegawai as $p)
                            <option value="{{ $p->id }}" {{ old('pegawai_id', $berka->pegawai_id) == $p->id ? 'selected' : '' }}>
                                {{ $p->nama }} (NIP: {{ $p->nip }})
                            </option>
                            @endforeach
                        </select>
                        @error('pegawai_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label for="nama_berkas">Nama Berkas <span class="text-danger">*</span></label>
                        <select class="form-control @error('nama_berkas') is-invalid @enderror" id="nama_berkas" name="nama_berkas" required>
                             @php
                                $jenisBerkas = ['KTP', 'Ijazah SMA/SMK', 'Ijazah S1', 'Ijazah S2', 'Transkrip Nilai', 'Sertifikat Keahlian', 'Surat Pengalaman Kerja', 'Lainnya'];
                            @endphp
                            @foreach($jenisBerkas as $jenis)
                                <option value="{{ $jenis }}" {{ old('nama_berkas', $berka->nama_berkas) == $jenis ? 'selected' : '' }}>{{ $jenis }}</option>
                            @endforeach
                        </select>
                        @error('nama_berkas')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label for="file">Ganti File (Opsional)</label>
                        <div class="input-group">
                            <div class="custom-file">
                                <input type="file" class="custom-file-input @error('file') is-invalid @enderror" id="file" name="file" onchange="previewFile()">
                                <label class="custom-file-label" for="file">Pilih file baru...</label>
                            </div>
                        </div>
                        <small class="form-text text-muted">Kosongkan jika tidak ingin mengganti file. Tipe: PDF, JPG, PNG. Max: 2MB.</small>
                        @error('file')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label for="tanggal_kadaluarsa">Tanggal Kadaluarsa (Opsional)</label>
                        <input type="date" class="form-control @error('tanggal_kadaluarsa') is-invalid @enderror" id="tanggal_kadaluarsa" name="tanggal_kadaluarsa" value="{{ old('tanggal_kadaluarsa', $berka->tanggal_kadaluarsa) }}">
                        @error('tanggal_kadaluarsa')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="col-md-5">
                    {{-- Kolom Kanan untuk Preview File --}}
                    <label>Preview File</label>
                    <div id="preview-container" class="text-center border p-2" style="min-height: 250px; display: flex; align-items: center; justify-content: center; background-color: #f4f6f9;">
                        {{-- Preview akan ditampilkan di sini oleh JavaScript --}}
                    </div>
                    <div id="pdf-controls" class="d-none text-center mt-2">
                        <button type="button" class="btn btn-secondary btn-sm" id="prev-page"><i class="fas fa-arrow-left"></i></button>
                        <span class="mx-2">Halaman <span id="page_num"></span> / <span id="page_count"></span></span>
                        <button type="button" class="btn btn-secondary btn-sm" id="next-page"><i class="fas fa-arrow-right"></i></button>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            <a href="{{ route('berkas.index') }}" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.10.377/pdf.min.js"></script>

<script>
    // Inisialisasi worker untuk PDF.js
    pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.10.377/pdf.worker.min.js';

    // Variabel global untuk state PDF
    let pdfDoc = null;
    let pageNum = 1;
    let pageIsRendering = false;
    let pageNumIsPending = null;
    const scale = 1.5;

    const previewContainer = document.getElementById('preview-container');
    const pdfControls = document.getElementById('pdf-controls');
    const pageNumSpan = document.getElementById('page_num');
    const pageCountSpan = document.getElementById('page_count');
    const fileInput = document.getElementById('file');
    const fileLabel = document.querySelector('.custom-file-label');

    // URL file yang sudah ada dari database
    const existingFileUrl = "{{ Storage::url($berka->file_path) }}";
    const existingFileType = "{{ strtolower(pathinfo($berka->file_path, PATHINFO_EXTENSION)) }}";

    // Fungsi untuk menampilkan preview file (bisa dari URL atau dari input)
    function displayPreview(source) {
        previewContainer.innerHTML = '';
        pdfControls.classList.add('d-none');

        // Jika sumber adalah file dari input
        if (source instanceof File) {
             fileLabel.textContent = source.name;
            if (source.type.startsWith('image/')) {
                // Tampilkan preview gambar
                const reader = new FileReader();
                reader.onload = e => {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.className = 'img-fluid img-thumbnail';
                    img.style.maxHeight = '300px';
                    previewContainer.appendChild(img);
                };
                reader.readAsDataURL(source);
            } else if (source.type === 'application/pdf') {
                // Tampilkan preview PDF
                const reader = new FileReader();
                reader.onload = e => renderPdf(new Uint8Array(e.target.result));
                reader.readAsArrayBuffer(source);
            } else {
                 previewContainer.innerHTML = `<div class="text-center p-4"><i class="fas fa-file fa-3x text-muted"></i><p class="mt-2 text-muted">Preview tidak tersedia.</p></div>`;
            }
        }
        // Jika sumber adalah URL dari file yang ada
        else if (typeof source === 'string') {
            if (['jpg', 'jpeg', 'png', 'gif'].includes(existingFileType)) {
                const img = document.createElement('img');
                img.src = source;
                img.className = 'img-fluid img-thumbnail';
                img.style.maxHeight = '300px';
                previewContainer.appendChild(img);
            } else if (existingFileType === 'pdf') {
                pdfjsLib.getDocument(source).promise.then(renderPdf, err => {
                    previewContainer.innerHTML = '<span class="text-danger">Gagal memuat preview PDF.</span>';
                });
            } else {
                previewContainer.innerHTML = `<div class="text-center p-4"><i class="fas fa-file fa-3x text-muted"></i><p class="mt-2 text-muted">Preview tidak tersedia.</p> <a href="${source}" target="_blank" class="btn btn-sm btn-info mt-2">Download File</a></div>`;
            }
        }
    }
    
    // Fungsi terpisah untuk merender PDF
    function renderPdf(data) {
        pdfjsLib.getDocument(data).promise.then(pdf => {
            pdfDoc = pdf;
            pageCountSpan.textContent = pdfDoc.numPages;
            pageNum = 1;
            renderPage(pageNum);
            pdfControls.classList.remove('d-none');
        }).catch(err => {
            previewContainer.innerHTML = '<span class="text-danger">Gagal memuat preview PDF.</span>';
        });
    }

    // Fungsi render per halaman
    const renderPage = num => {
        pageIsRendering = true;
        pdfDoc.getPage(num).then(page => {
            const viewport = page.getViewport({ scale });
            const canvas = document.createElement('canvas');
            const context = canvas.getContext('2d');
            canvas.height = viewport.height;
            canvas.width = viewport.width;
            canvas.style.maxWidth = '100%';
            canvas.style.height = 'auto';

            page.render({ canvasContext: context, viewport: viewport }).promise.then(() => {
                pageIsRendering = false;
                if (pageNumIsPending !== null) {
                    renderPage(pageNumIsPending);
                    pageNumIsPending = null;
                }
            });
            previewContainer.innerHTML = '';
            previewContainer.appendChild(canvas);
            pageNumSpan.textContent = num;
        });
    };

    const queueRenderPage = num => {
        if (pageIsRendering) {
            pageNumIsPending = num;
        } else {
            renderPage(num);
        }
    };
    
    // Event listeners
    document.getElementById('prev-page').addEventListener('click', () => { if (pageNum <= 1) return; pageNum--; queueRenderPage(pageNum); });
    document.getElementById('next-page').addEventListener('click', () => { if (pageNum >= pdfDoc.numPages) return; pageNum++; queueRenderPage(pageNum); });
    fileInput.addEventListener('change', () => {
        if (fileInput.files && fileInput.files[0]) {
            displayPreview(fileInput.files[0]);
        }
    });

    // Tampilkan preview file yang sudah ada saat halaman dimuat
    displayPreview(existingFileUrl);

</script>
@endsection