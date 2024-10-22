@extends('layouts.app')

@section('title', 'Tambah Data Mahasiswa')

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
            <a href="{{route('mahasiswa.admin')}}" class="btn btn-primary"><i class="fas fa-arrow-left"></i> Kembali</a>
        </div>
        <div class="card-body">
            <form action="{{route('mahasiswa.admin.store')}}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-lg-6 col-md-12 col-sm-12">
                        <div class="form-group mb-3">
                            <label for="" class="mb-2">No Induk Mahasiswa</label>
                            <input type="text" name="username" id="username" class="form-control @error('username') is-invalid @enderror">
                            @error('username')
                                <span class="invalid-feedback">
                                    {{$message}}
                                </span>
                            @enderror   
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-12 col-sm-12">
                        <div class="form-group mb-3">
                            <label for="" class="mb-2">Nama Lengkap</label>
                            <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror">
                            @error('name')
                                <span class="invalid-feedback">
                                    {{$message}}
                                </span>
                            @enderror   
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-12 col-sm-12">
                        <div class="form-group mb-3">
                            <label for="" class="mb-2">Email Addres</label>
                            <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror">
                            @error('email')
                                <span class="invalid-feedback">
                                    {{$message}}
                                </span>
                            @enderror   
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-12 col-sm-12">
                        <div class="form-group mb-3">
                            <label for="" class="mb-2">Jenis Kelamin</label>
                            <select name="gender" id="gender" class="form-control @error('gender') is-invalid @enderror">
                                <option value="">- Pilih -</option>
                                <option value="Laki-laki">Laki-laki</option>
                                <option value="Perempuan">Perempuan</option>
                            </select>
                            @error('gender')
                                <span class="invalid-feedback">
                                    {{$message}}
                                </span>
                            @enderror   
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-12 col-sm-12">
                        <div class="form-group mb-3">
                            <label for="" class="mb-2">Tempat Lahir</label>
                            <input type="text" name="tmp_lahir" id="tmp_lahir" class="form-control @error('tmp_lahir') is-invalid @enderror">
                            @error('tmp_lahir')
                                <span class="invalid-feedback">
                                    {{$message}}
                                </span>
                            @enderror   
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-12 col-sm-12">
                        <div class="form-group mb-3">
                            <label for="" class="mb-2">Tanggal Lahir</label>
                            <input type="date" name="tgl_lahir" id="tgl_lahir" class="form-control @error('tgl_lahir') is-invalid @enderror">
                            @error('tgl_lahir')
                                <span class="invalid-feedback">
                                    {{$message}}
                                </span>
                            @enderror   
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-12 col-sm-12">
                        <div class="form-group mb-3">
                            <label for="" class="mb-2">No Telephone</label>
                            <input type="text" name="telp" id="telp" class="form-control @error('telp') is-invalid @enderror">
                            @error('telp')
                                <span class="invalid-feedback">
                                    {{$message}}
                                </span>
                            @enderror   
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-12 col-sm-12">
                        <div class="form-group mb-3">
                            <label for="" class="mb-2">Alamat</label>
                            <input type="text" name="address" id="address" class="form-control @error('address') is-invalid @enderror">
                            @error('address')
                                <span class="invalid-feedback">
                                    {{$message}}
                                </span>
                            @enderror   
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-12 col-sm-12">
                        <div class="form-group mb-3">
                            <label for="" class="mb-2">Password</label>
                            <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror">
                            @error('password')
                                <span class="invalid-feedback">
                                    {{$message}}
                                </span>
                            @enderror   
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-12 col-sm-12">
                        <div class="form-group mb-3">
                            <label for="" class="mb-2">Konfirmasi Password</label>
                            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control @error('password_confirmation') is-invalid @enderror">
                            @error('password_confirmation')
                                <span class="invalid-feedback">
                                    {{$message}}
                                </span>
                            @enderror   
                        </div>
                    </div>
                </div>

                <button type="reset" class="btn btn-secondary float-left">Batal</button>
                <button type="submit" class="btn btn-primary float-right">Tambah</button>
            </form>
        </div>
    </div>
@endsection 

@push('js')
    
@endpush