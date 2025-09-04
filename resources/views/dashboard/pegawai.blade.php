@extends('layouts.dashboard')

@section('title', 'Dashboard Pegawai')
@section('page-title', 'Dashboard')

@section('main-content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Selamat Datang!</h3>
            </div>
            <div class="card-body">
                <h5 class="font-weight-bold">Halo, {{ auth()->user()->name }}!</h5>
                <p>Ini adalah halaman dashboard Anda. Fitur lebih lanjut akan segera ditambahkan.</p>
            </div>
        </div>
    </div>
</div>
@endsection