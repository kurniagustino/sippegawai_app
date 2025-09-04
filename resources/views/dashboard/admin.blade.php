@extends('layouts.dashboard')

@section('title', 'Dashboard Admin')
@section('page-title', 'Dashboard Admin')

@section('main-content')
<div class="row">
    {{-- Stat Box: Total Pegawai --}}
    <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3>150</h3>
                <p>Total Pegawai</p>
            </div>
            <div class="icon">
                <i class="fas fa-users"></i>
            </div>
            <a href="{{ route('pegawai.index') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    {{-- Stat Box: Berkas Kadaluarsa --}}
    <div class="col-lg-3 col-6">
        <div class="small-box bg-danger">
            <div class="inner">
                <h3>4</h3>
                <p>Berkas Kadaluarsa</p>
            </div>
            <div class="icon">
                <i class="fas fa-file-excel"></i>
            </div>
            <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    {{-- Stat Box: Hampir Kadaluarsa --}}
    <div class="col-lg-3 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3>8</h3>
                <p>Hampir Kadaluarsa</p>
            </div>
            <div class="icon">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    {{-- Stat Box: User Aktif --}}
    <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>2</h3>
                <p>User Aktif</p>
            </div>
            <div class="icon">
                <i class="fas fa-user-plus"></i>
            </div>
            <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
</div>

<div class="row">
    {{-- Area Chart --}}
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Statistik Pegawai</h3>
            </div>
            <div class="card-body">
                <div style="height: 300px; background-color: #f4f6f9; display: flex; align-items: center; justify-content: center; color: #888;">
                    Placeholder untuk Area Chart
                </div>
            </div>
        </div>
    </div>
    {{-- Pie Chart --}}
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Komposisi Jabatan</h3>
            </div>
            <div class="card-body">
                <div style="height: 300px; background-color: #f4f6f9; display: flex; align-items: center; justify-content: center; color: #888;">
                    Placeholder untuk Pie Chart
                </div>
            </div>
        </div>
    </div>
</div>
@endsection