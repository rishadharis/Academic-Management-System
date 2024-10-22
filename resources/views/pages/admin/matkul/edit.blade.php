@extends('layouts.app')

@section('title', 'Edit Mata Kuliah')

@push('css')
    
@endpush

@section('content')
    @if (session()->has('success'))
        <div class="alert alert-primary">
            <strong>
                {{session()->get('success')}}
            </strong>
        </div>
    @endif
    <div class="card card-primary">
        <div class="card-header">
            <a href="{{route('matkul.admin')}}" class="btn btn-primary"><i class="fas fa-arrow-left"></i> Kembali</a>
        </div>
        <div class="card-body">
            <form action="{{route('matkul.admin.update', ['id' => $matkul->id])}}" method="POST">
                @csrf
                @method("PUT")
                <div class="form-group mb-3">
                    <label for="" class="mb-2">Kode Mata Kuliah</label>
                    <input type="text" name="kode_mk" id="kode_mk" class="form-control @error('kode_mk') is-invalid @enderror" value="{{$matkul->kode_mk}}">
                    @error('kode_mk')
                        <span class="invalid-feedback">
                            {{$message}}
                        </span>
                    @enderror
                </div>
                <div class="form-group mb-3">
                    <label for="" class="mb-2">Nama Mata Kuliah</label>
                    <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{$matkul->name}}">
                    @error('name')
                        <span class="invalid-feedback">
                            {{$message}}
                        </span>
                    @enderror
                </div>
                <div class="form-group mb-3">
                    <label for="" class="mb-2">Deskripsi</label>
                    <input type="text" name="desc" id="desc" class="form-control @error('desc') is-invalid @enderror" value="{{$matkul->desc}}">
                    @error('desc')
                        <span class="invalid-feedback">
                            {{$message}}
                        </span>
                    @enderror
                </div>

                <button type="reset" class="btn btn-secondary float-left">Batal</button>
                <button type="submit" class="btn btn-primary float-right">Ubah</button>
            </form>
        </div>
    </div>
@endsection 

@push('js')
    
@endpush