@extends('layouts.app')

@section('title')
    Edit Kelas - {{$kelas->kode_kelas}}/{{$kelas->matkul->name}}
@endsection 

@push('css')
    <link rel="stylesheet" href="{{asset('')}}modules/select2/dist/css/select2.min.css">
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
            <a href="{{route('kelas.admin')}}" class="btn btn-primary"><i class="fas fa-arrow-left"></i> Kembali</a>
        </div>
        <div class="card-body">
            <form action="{{route('kelas.admin.update', ['id' => $kelas->id])}}" method="POST">
                @csrf
                @method("PUT")
                <div class="row">
                    <div class="col-lg-6 col-md-12 col-sm-12">
                        <div class="form-group mb-3">
                            <label for="" class="mb-2">Tahun Akademik</label>
                            <select name="tahun_akademiks_id" id="tahun_akademiks_id" class="form-control @error('tahun_akademiks_id') is-invalid @enderror select2">
                                <option value="">- Pilih - </option>
                                @foreach ($akademik as $akd)
                                    <option value="{{$akd->id}}" {{$kelas->tahun_akademiks_id == $akd->id ? 'selected' : ''}}>{{$akd->name}}</option>
                                @endforeach
                            </select>
                            @error('tahun_akademiks_id')
                                <span class="invalid-feedback">
                                    {{$message}}
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-12 col-sm-12">
                        <div class="form-group mb-3">
                            <label for="" class="mb-2">Mata Kuliah</label>
                            <select name="matkuls_id" id="matkuls_id" class="form-control @error('matkuls_id') is-invalid @enderror select2">
                                <option value="">- Pilih - </option>
                                @foreach ($matkul as $mk)
                                    <option value="{{$mk->id}}" {{$kelas->matkuls_id == $mk->id ? 'selected' : ''}}>{{$mk->kode_mk}} - {{$mk->name}}</option>
                                @endforeach
                            </select>
                            @error('matkuls_id')
                                <span class="invalid-feedback">
                                    {{$message}}
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-12 col-sm-12">
                        <div class="form-group mb-3">
                            <label for="" class="mb-2">Kode Kelas</label>
                            <input type="text" name="kode_kelas" id="kode_kelas" class="form-control @error('kode_kelas') is-invalid @enderror" value="{{$kelas->kode_kelas}}">
                            @error('kode_kelas')
                                <span class="invalid-feedback">
                                    {{$message}}
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-12 col-sm-12">
                        <div class="form-group mb-3">
                            <label for="" class="mb-2">Jumlah SKS</label>
                            <input type="number" name="sks" id="sks" class="form-control @error('sks') is-invalid @enderror" value="{{$kelas->sks}}">
                            @error('sks')
                                <span class="invalid-feedback">
                                    {{$message}}
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-12 col-sm-12">
                        <div class="form-group mb-3">
                            <label for="" class="mb-2">Tanggal UTS</label>
                            <input type="date" name="tgl_uts" id="tgl_uts" class="form-control @error('tgl_uts') is-invalid @enderror" value="{{$kelas->tgl_uts}}">
                            @error('tgl_uts')
                                <span class="invalid-feedback">
                                    {{$message}}
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-12 col-sm-12">
                        <div class="form-group mb-3">
                            <label for="" class="mb-2">Tanggal UAS</label>
                            <input type="date" name="tgl_uas" id="tgl_uas" class="form-control @error('tgl_uas') is-invalid @enderror" value="{{$kelas->tgl_uas}}">
                            @error('tgl_uas')
                                <span class="invalid-feedback">
                                    {{$message}}
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-12 col-sm-12">
                        <div class="form-group mb-3">
                            <label for="" class="mb-2">Ruangan (optional)</label>
                            <input type="text" name="ruangan" id="ruangan" class="form-control @error('ruangan') is-invalid @enderror" value="{{$kelas->ruangan}}">
                            @error('ruangan')
                                <span class="invalid-feedback">
                                    {{$message}}
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-12 col-sm-12">
                        <div class="form-group mb-3">
                            <label for="" class="mb-2">Deskripsi Kelas</label>
                            <input type="text" name="desc" id="desc" class="form-control @error('desc') is-invalid @enderror" value="{{$kelas->desc}}">
                            @error('desc')
                                <span class="invalid-feedback">
                                    {{$message}}
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <div class="form-group mb-3">
                            <label for="" class="mb-2">Dosen Pengampu</label>
                            <select name="users_id[]" id="users_id" class="form-control @error('users_id') is-invalid @enderror select2" multiple>
                                <option value="">- Pilih - </option>
                                @foreach ($dosen as $dsn)
                                    <option value="{{$dsn->id}}" {{ $kelas->dosen->contains('users_id', $dsn->id) ? 'selected' : '' }}>{{$dsn->username}} - {{$dsn->name}}</option>
                                @endforeach
                            </select>
                            @error('users_id')
                                <span class="invalid-feedback">
                                    {{$message}}
                                </span>
                            @enderror
                        </div>
                    </div>
                </div>

                <button type="reset" class="btn btn-secondary float-left">Batal</button>
                <button type="submit" class="btn btn-primary float-right">Ubah</button>
            </form>
        </div>
    </div>
@endsection 

@push('js')
    <script src="{{asset('')}}modules/select2/dist/js/select2.full.min.js"></script>
@endpush