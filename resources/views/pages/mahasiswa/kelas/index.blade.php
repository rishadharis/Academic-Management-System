@extends('layouts.app')

@section('title')
    Kelas - {{$kelas->kode_kelas}}/{{$kelas->matkul->name}}
@endsection

@section('content')
<div class="accordion">
    <div class="accordion-header" role="button" data-toggle="collapse" data-target="#panel-body-1" aria-expanded="true">
        <div class="d-flex justify-content-between">
            <div>
                <h4 class="mb-3 mt-2">{{$kelas->kode_kelas}}</h4>
                <h5>{{$kelas->matkul->name}}</h5>
                <h4 class="mt-2">{{$kelas->sks}} SKS</h4>
                <br>
                <h4 class="mt-2">Tahun Akademik - {{$kelas->akademik->name}}</h4>
            </div>
            <div>
                <h4 class="mb-3 mt-2">Dosen Pengampu</h4>
                <ul>
                    @foreach ($kelas->dosen as $dsn)
                        <li>{{$dsn->user->name}}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-3 col-md-6 col-sm-12">
        <a href="{{route('jadwal.mahasiswa')}}" style="text-decoration:none">
            <div class="wizard-steps">
                <div class="wizard-step wizard-step-danger">
                    <div class="wizard-step-icon">
                        <i class="fas fa-chalkboard"></i>
                    </div>
                    <div class="wizard-step-label">
                        Jadwal
                    </div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-lg-3 col-md-6 col-sm-12">
        <a href="{{route('assigment.mahasiswa')}}" style="text-decoration: none">
            <div class="wizard-steps">
                <div class="wizard-step wizard-step-danger">
                    <div class="wizard-step-icon">
                        <i class="fas fa-file-pdf"></i>
                    </div>
                    <div class="wizard-step-label">
                        Tugas
                    </div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-12">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive-lg">
                    <table class="table table-bordered table-striped table-hover text-center">
                        <thead class="bg-primary">
                            <tr>
                                <th class="text-white">Nilai UTS</th>
                                <th class="text-white">Nilai UAS</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    {{$nilai ? $nilai->nilai_uts : 'Belum Di Isi'}}
                                </td>
                                <td>
                                    {{$nilai ? $nilai->nilai_uas : 'Belum Di Isi'}}
                                </td>
                            </tr>
                        </tbody>
                        <tfoot class="bg-primary">
                            <tr>
                                <th class="text-white" colspan="2">Nilai Tugas (Rata Rata)</th>
                            </tr>
                            <tr>
                                <th class="text-white" colspan="2">{{number_format($rataRataNilaiTugas, 2)}}</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection