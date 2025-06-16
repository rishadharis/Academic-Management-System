@extends('layouts.app')

@section('title', 'Dashboard')

@push('css')
    
@endpush

@section('content')
    <div class="row">
        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
            <div class="card card-statistic-1">
                <div class="card-icon bg-primary">
                    <i class="fas fa-users"></i>
                </div>
                <div class="card-wrap">
                    <div class="card-header">
                        <h4>Jumlah Mahasiswa</h4>
                    </div>
                    <div class="card-body">
                        {{$mhsCount}}
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
            <div class="card card-statistic-1">
                <div class="card-icon bg-danger">
                    <i class="fas fa-user"></i>
                </div>
                <div class="card-wrap">
                    <div class="card-header">
                        <h4>Jumlah Dosen</h4>
                    </div>
                    <div class="card-body">
                        {{$dosenCount}}
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
            <div class="card card-statistic-1">
                <div class="card-icon bg-warning">
                    <i class="fas fa-home"></i>
                </div>
                <div class="card-wrap">
                    <div class="card-header">
                        <h4>Jumlah Kelas</h4>
                    </div>
                    <div class="card-body">
                        {{$kelasCount}}
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
            <div class="card card-statistic-1">
                <div class="card-icon bg-success">
                    <i class="fas fa-book-open"></i>
                </div>
                <div class="card-wrap">
                    <div class="card-header">
                        <h4>Jumlah Mata Kuliah</h4>
                    </div>
                    <div class="card-body">
                        {{$mkCount}}
                    </div>
                </div>
            </div>
        </div>   
    </div>
@endsection 

@push('js')
    
@endpush