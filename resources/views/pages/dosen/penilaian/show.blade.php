@extends('layouts.app')

@section('title')
    Penilaian UTS/UAS - {{$kelas->kode_kelas}}/{{$kelas->matkul->name}}
@endsection

@push('css')
    
@endpush

@section('content')
    <div class="card card-primary mb-3">
        <div class="card-header">
            <strong>
                Detail Kelas
            </strong>
        </div>
        <div class="card-body">
            <div class="table-responsive-lg">
                <table class="table table-striped">
                    <tr>
                        <th>Kode Kelas</th>
                        <td style="width: 5%">:</td>
                        <td>{{$kelas->kode_kelas}}</td>
                    </tr>
                    <tr>
                        <th>Mata Kuliah</th>
                        <td style="width: 5%">:</td>
                        <td>
                            {{$kelas->matkul->kode_mk}} - {{$kelas->matkul->name}}
                        </td>
                    </tr>
                    <tr>
                        <th>Jumlah SKS</th>
                        <td style="width: 5%">:</td>
                        <td>
                            {{$kelas->sks}} SKS
                        </td>
                    </tr>
                    <tr>
                        <th>Tahun Akademik</th>
                        <td style="width: 5%">:</td>
                        <td>
                            {{$kelas->akademik->name}}
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>
                        {{$error}}
                    </li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (session()->has('success'))
        <div class="alert alert-primary">
            <strong>
                {{session()->get('success')}}
            </strong>
        </div>
    @endif

    <div class="card">
        <div class="card-header">
            <strong>
                Daftar Mahasiswa
            </strong>
        </div>
        <div class="card-body">
            <form action="{{route('penilaian.dosen.store')}}" method="POST">
                @csrf
                <input type="hidden" name="kelas_id" id="kelas_id" value="{{$kelas->id}}">
                <div class="table-responsive-lg">
                    <table class="table table-bordered table-striped table-hover" id="table" style="width: 100%">
                        <thead class="bg-primary">
                            <tr>
                                <th class="text-white">NIM</th>
                                <th class="text-white">Nama Mahasiswa</th>
                                <th class="text-white text-center">Nilai UTS</th>
                                <th class="text-white text-center">Nilai UAS</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($kelasMahasiswa as $item)
                                <tr>
                                    <input type="hidden" name="users_id[]" id="users_id" value="{{$item->user->id}}">
                                    <td>{{$item->user->username}}</td>
                                    <td>{{$item->user->name}}</td>
                                    @php
                                        $isDone = false;
                                        $nilai_uts = null;
                                        $nilai_uas = null;
                                        foreach ($item->user->nilai as $nli) {
                                            if ($nli->users_id == $item->users_id) {
                                                $isDone = true;
                                                $nilai_uts = $nli->nilai_uts;
                                                $nilai_uas = $nli->nilai_uas;
                                            }
                                        }
                                    @endphp
                                    @if ($isDone)
                                        <td>
                                            <center>
                                                <input type="text" name="nilai_uts[]" id="nilai_uts" value="{{$nilai_uts}}" class="form-control text-center" style="width: 50%">
                                            </center>
                                        </td>
                                        <td>
                                            <center>
                                                <input type="text" name="nilai_uas[]" id="nilai_uas" value="{{$nilai_uas}}" class="form-control text-center" style="width: 50%">
                                            </center>
                                        </td>
                                    @else
                                        <td>
                                            <center>
                                                <input type="text" name="nilai_uts[]" id="nilai_uts" class="form-control text-center" style="width: 50%">
                                            </center>
                                        </td>
                                        <td>
                                            <center>
                                                <input type="text" name="nilai_uas[]" id="nilai_uas" class="form-control text-center" style="width: 50%">
                                            </center>
                                        </td>
                                    @endif
                                </tr>
                            @empty
                                <tr>
                                    <td>Belum Ada Mahasiswa Di Kelas {{$kelas->kode_kelas}}/{{$kelas->matkul->name}}</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <button type="submit" class="btn btn-primary float-right">Simpan</button>
            </form>
        </div>
    </div>
@endsection 

@push('js')
    
@endpush