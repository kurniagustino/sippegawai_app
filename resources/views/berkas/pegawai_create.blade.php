@extends('layouts.dashboard')

@section('title', 'Unggah Berkas Baru')
@section('page-title', 'Unggah Berkas Baru')

@section('main-content')
<div class="card card-primary">
    <div class="card-header">
        <h3 class="card-title">Form Unggah Berkas</h3>
    </div>
    <form action="{{ route('pegawai.berkas.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="card-body">
            <div class="row">
                <div class="col-md-7">
                    <div class="form-group">
                        <label for="nama_berkas">Nama Berkas <span class="text-danger">*</span></label>
                        <select class="form-control @error('nama_berkas') is-invalid @enderror" id="nama_berkas" name="nama_berkas" required>
                            <option value="">-- Pilih Jenis Berkas --</option>
                            @php
                                $options = [
                                    'KTP' => 'KTP (Kartu Tanda Penduduk)',
                                    'Ijazah SMA/SMK' => 'Ijazah SMA/SMK',
                                    'Ijazah S1' => 'Ijazah S1',
                                    'Ijazah S2' => 'Ijazah S2',
                                    'Transkrip Nilai' => 'Transkrip Nilai',
                                    'Sertifikat Keahlian' => 'Sertifikat Keahlian',
                                    'Surat Pengalaman Kerja' => 'Surat Pengalaman Kerja',
                                    'Lainnya' => 'Lainnya',
                                ];
                            @endphp
                            @foreach($options as $value => $label)
                                @php
                                    $isUploaded = in_array($value, $uploaded_berkas);
                                    $labelText = $isUploaded ? $label . ' - (selesai)' : $label;
                                    $style = $isUploaded ? 'color: green;' : '';
                                @endphp
                                <option value="{{ $value }}" {{ old('nama_berkas') == $value ? 'selected' : '' }} style="{{ $style }}" {{ $isUploaded ? 'disabled' : '' }}>
                                    {{ $labelText }}
                                </option>
                            @endforeach
                        </select>
                        @error('nama_berkas')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label for="file">Unggah File <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <div class="custom-file">
                                <input type="file" class="custom-file-input @error('file') is-invalid @enderror" id="file" name="file" required onchange="previewFile()">
                                <label class="custom-file-label" for="file">Pilih file...</label>
                            </div>
                        </div>
                        <small class="form-text text-muted">Tipe file: PDF, JPG, PNG. Maksimal ukuran: 2MB.</small>
                        @error('file')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label for="tanggal_kadaluarsa">Tanggal Kadaluarsa (Opsional)</label>
                        <input type="date" class="form-control @error('tanggal_kadaluarsa') is-invalid @enderror" id="tanggal_kadaluarsa" name="tanggal_kadaluarsa" value="{{ old('tanggal_kadaluarsa') }}">
                        @error('tanggal_kadaluarsa')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="col-md-5">
                    <label>Preview File</label>
                    <div id="preview-container" class="text-center border p-2" style="min-height: 250px; display: flex; align-items: center; justify-content: center; background-color: #f4f6f9;">
                        <span class="text-muted">Pilih file untuk melihat preview</span>
                    </div>
                    <div id="pdf-controls" class="d-none text-center mt-2">
                        <button type="button" class="btn btn-secondary btn-sm" id="prev-page">
                            <i class="fas fa-arrow-left"></i> Sebelumnya
                        </button>
                        <span class="mx-2">Halaman <span id="page_num"></span> dari <span id="page_count"></span></span>
                        <button type="button" class="btn btn-secondary btn-sm" id="next-page">
                            Selanjutnya <i class="fas fa-arrow-right"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Unggah</button>
            <a href="{{ route('pegawai.berkas.index') }}" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.10.377/pdf.min.js"></script>

<script>
    pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.10.377/pdf.worker.min.js';
    let pdfDoc = null;
    let pageNum = 1;
    let pageIsRendering = false;
    let pageNumIsPending = null;
    const scale = 1.5;
    const previewContainer = document.getElementById('preview-container');
    const pdfControls = document.getElementById('pdf-controls');
    const pageNumSpan = document.getElementById('page_num');
    const pageCountSpan = document.getElementById('page_count');

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
            const renderContext = {
                canvasContext: context,
                viewport: viewport
            };
            page.render(renderContext).promise.then(() => {
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

    document.getElementById('prev-page').addEventListener('click', () => {
        if (pageNum <= 1) return;
        pageNum--;
        queueRenderPage(pageNum);
    });

    document.getElementById('next-page').addEventListener('click', () => {
        if (pageNum >= pdfDoc.numPages) return;
        pageNum++;
        queueRenderPage(pageNum);
    });

    function previewFile() {
        const fileInput = document.getElementById('file');
        const fileLabel = document.querySelector('.custom-file-label');
        previewContainer.innerHTML = '';
        pdfControls.classList.add('d-none');
        if (fileInput.files && fileInput.files[0]) {
            const file = fileInput.files[0];
            fileLabel.textContent = file.name;
            if (file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.className = 'img-fluid img-thumbnail';
                    img.style.maxHeight = '300px';
                    previewContainer.appendChild(img);
                }
                reader.readAsDataURL(file);
            } else if (file.type === 'application/pdf') {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const typedarray = new Uint8Array(e.target.result);
                    pdfjsLib.getDocument(typedarray).promise.then(pdf => {
                        pdfDoc = pdf;
                        pageCountSpan.textContent = pdfDoc.numPages;
                        pageNum = 1;
                        renderPage(pageNum);
                        pdfControls.classList.remove('d-none');
                    }).catch(err => {
                        previewContainer.innerHTML = '<span class="text-danger">Gagal memuat preview PDF.</span>';
                    });
                };
                reader.readAsArrayBuffer(file);
            } else {
                previewContainer.innerHTML = `<div class="text-center p-4"><i class="fas fa-file fa-3x text-muted"></i><p class="mt-2 text-muted">Preview tidak tersedia untuk<br><strong>${file.name}</strong></p></div>`;
            }
        }
    }
</script>
@endsection