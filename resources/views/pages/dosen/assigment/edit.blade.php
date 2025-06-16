@extends('layouts.app')

@section('title')
    Edit Tugas Kelas - {{$assigment->kelas->kode_kelas}}/{{$assigment->kelas->matkul->name}}
@endsection

@push('css')
    <link rel="stylesheet" href="{{asset('')}}modules/summernote/summernote-bs4.css">
@endpush

@section('content')
    <div class="card card-primary">
        <div class="card-header">
            <a href="{{route('kelas.dosen.show', ['id' => $assigment->kelas->id])}}" class="btn btn-primary"><i class="fas fa-arrow-left"></i> Kembali</a>
        </div>
        <div class="card-body">
            <form action="{{route('kelas.dosen.assigment.update', ['id' => $assigment->id])}}" method="POST">
                @csrf
                @method("PUT")
                <div class="form-group mb-3">
                    <label for="" class="mb-2">Nama Tugas</label>
                    <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{$assigment->name}}">
                    @error('name')
                        <span class="invalid-feedback">
                            {{$message}}
                        </span>
                    @enderror
                </div>
                <div class="form-group mb-3">
                    <label for="" class="mb-2">Deskripsi Tugas</label>
                    <textarea name="desc" id="desc" cols="30" rows="10" class="form-control @error('desc') is-invalid @enderror summernote">{{$assigment->desc}}</textarea>
                    @error('desc')
                        <span class="invalid-feedback">
                            {{$message}}
                        </span>
                    @enderror
                </div>
                <div class="form-group mb-3">
                    <label for="" class="mb-2">Deadline</label>
                    <input type="date" name="deadline" id="deadline" class="form-control @error('deadline') is-invalid @enderror" value="{{$assigment->deadline}}">
                    @error('deadline')
                        <span class="invalid-feedback">
                            {{$message}}
                        </span>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary float-right">Ubah</button>
            </form>
        </div>
    </div>
@endsection 

@push('js')
    <script src="{{asset('')}}modules/summernote/summernote-bs4.js"></script>
@endpush