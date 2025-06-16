@extends('layouts.app')

@section('title', 'Dashboard')

@push('css')
    <link rel="stylesheet" href="{{asset('')}}modules/fullcalendar/fullcalendar.min.css">
@endpush

@section('content')
    <div class="d-flex justify-content-between  mb-3">
        <div>
            <h6 class="mt-3">
                @if (!empty($akademiks_id))
                    @php
                        $filteredAkademik = $allAkademik->where('id', $akademiks_id)->first();
                    @endphp
                    Tahun Ajaran - {{$filteredAkademik->name}}
                @else 
                    Tahun Ajaran - {{$latestAkademik ? $latestAkademik->name : 'Belum Ada Tahun Ajaran'}}
                @endif
            </h6>
        </div>
        <div>
            <form action="{{route('dashboard.mahasiswa')}}">
                <div class="input-group mb-3">
                    <select name="tahun_akademiks_id" id="tahun_akademiks_id" class="form-control">
                        @foreach ($allAkademik as $akd)
                            <option value="{{$akd->id}}" {{$akademiks_id == $akd->id ? 'selected' : ''}}>{{$akd->name}}</option>
                        @endforeach
                    </select>
                    <div class="input-group-append">
                        <button class="btn btn-outline-primary" type="submit" id="button-addon2"><i class="fas fa-filter"></i></button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @if (session()->has('success'))
        <div class="alert alert-primary">
            <strong>
                {{session()->get('success')}}
            </strong>
        </div>
    @endif

    <div class="row">
        <div class="col-lg-6 col-md-12 col-sm-12">
            <div class="card">
                <div class="card-header">
                    <strong>
                        Kalender
                    </strong>
                </div>
                <div class="card-body">
                    <div id="calendar"></div>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-12 col-sm-12">
            <div class="card">
                <div class="card-header">
                    <strong>
                        Tugas
                    </strong>
                </div>
                <div class="card-body">
                    @foreach ($tugasAll as $tgs)
                        <div class="d-flex justify-content-between mb-3">
                            <div>
                                <h6>{{$tgs['name']}}</h6>
                                <strong>{{$tgs['kelas']['matkul']['name']}}</strong> 
                                <p>{{$tgs['kelas']['kode_kelas']}}</p>
                            </div>
                            <div>
                                <p>
                                    <strong>{{\Carbon\Carbon::parse($tgs['deadline'])->translatedFormat('d F Y')}}</strong>
                                </p>
                                @if (\Carbon\Carbon::parse($tgs['deadline'])->isFuture())
                                     @php
                                        $isDone = false;
                                        foreach ($tgs['assigment_upload'] as $tgsUp) {
                                            if ($tgsUp['assigments_id'] == $tgs['id']) {
                                                $isDone = true;
                                                $file = $tgsUp['file'];
                                                break;
                                            }
                                        }
                                    @endphp
                                    @if ($isDone)
                                        <a href="javascript:void(0)" class="btn btn-outline-success w-100 disabled">Done</a>
                                    @else
                                        <a href="javascript:void(0)" onclick="return UploadTugas('{{$tgs['id']}}')" class="btn btn-outline-primary w-100">Upload</a>
                                    @endif
                                @else
                                    <a href="javascript:void(0)" class="btn btn-outline-danger w-100 disabled">Expired</a>
                                @endif
                            </div>
                        </div>
                        <hr class="divide mb-4">
                    @endforeach

                    <a href="{{route('assigment.mahasiswa')}}" class="btn btn-primary w-100">Lihat Semua Tugas <i class="fas fa-arrow-right"></i></a>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-12 col-sm-12">
            <div class="card">
                <div class="card-header">
                    <strong>
                        Jadwal Terdekat
                    </strong>
                </div>
                <div class="card-body">
                    @foreach ($jadwalTerdekat as $index => $item)
                        <div class="d-flex justify-content-between mb-3">
                            <div>
                                <h6>{{$item['kelas']['matkul']['name']}}</h6>
                                {{$item['kelas']['kode_kelas']}}
                            </div>
                            <div>
                                <h6>{{\Carbon\Carbon::parse($item['tanggal'])->translatedFormat('d F Y')}}</h6>
                                {{$item['start_time']}} - {{$item['end_time']}}
                            </div>
                        </div>
                        <hr class="divide mb-4">
                    @endforeach
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-12 col-sm-12">
            <div class="card">
                <div class="card-header">
                    <strong>
                        Kelas
                    </strong>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover table-sm" id="table">
                            <thead class="bg-primary">
                                <tr>
                                    <th class="text-white">Kelas</th>
                                    <th class="text-white">SKS</th>
                                    <th class="text-white">Dosen Pengampu</th>
                                    <th class="text-white">#</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($kelasAll as $kls)
                                    <tr>
                                        <td>
                                            {{$kls['kode_kelas']}} - {{$kls['matkul']['name']}}
                                        </td>
                                        <td>
                                            {{$kls['sks']}}
                                        </td>
                                        <td>
                                            <ul>
                                                @foreach ($kls['dosen'] as $dsn)
                                                    <li>
                                                        {{$dsn['user']['name']}}
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </td>
                                        <td>
                                            <a href="{{route('kelas.mahasiswa.show', ['id' => $kls['id']])}}" class="btn btn-outline-primary btn-sm">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection 

@push('modal')
    <div class="modal fade" id="tugasModal" tabindex="-1" aria-labelledby="tugasModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="tugasModalLabel">Upload Tugas</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{route('assigment.mahasiswa.store')}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" name="assigments_id" id="assigments_id">
                        <div class="row">
                            <div class="col-lg-3 col-md-6 col-sm-12">
                                Kelas
                                <h6 id="kls">Nama Kelas</h6>
                            </div>
                            <div class="col-lg-3 col-md-6 col-sm-12">
                                Tugas
                                <h6 id="tgs">Nama Tugas</h6>
                            </div>
                            <div class="col-lg-3 col-md-6 col-sm-12">
                                Deadline
                                <h6 id="deadline">Nama Tugas</h6>
                            </div>
                            <div class="col-lg-3 col-md-6 col-sm-12">
                                Dosen Pengampu
                                <ul id="dsn">
                                    
                                </ul>
                            </div>
                            <div class="col-lg-12 col-md-12 col-sm-12">
                                Deskripsi
                                <p id="desc">
                                </p>
                            </div>
                            <div class="col-lg-12 col-md-12 col-sm-12">
                                <div class="form-group">
                                    <label for="">Upload File</label>
                                    <input type="file" name="file" id="file" class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Upload</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endpush

@push('js')
    <script src="{{asset('')}}modules/fullcalendar/fullcalendar.min.js"></script>
    <script>
        $(document).ready(function() {
            
            $("#table").DataTable()

            $("#calendar").fullCalendar({
                locale: 'id',
                height: 'auto',
                header: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'month,agendaWeek'
                },
                eventSources: [
                    {
                        url: "{{route('jadwal.mahasiswa.calendar')}}"
                    }
                ],
            });
        })

        function UploadTugas(id) {
            $.ajax({
                url: `{{url('portal/mahasiswa/assigment/${id}/show')}}`,
                method: "GET",
                dataType: "json",
                success: function(data) {
                    $("#tugasModal").modal('show')
                    let datas = data.data;
                    $("#assigments_id").val(datas.id);
                    $("#kls").html(datas.kelas.kode_kelas + ' - ' + datas.kelas.matkul.name);
                    $("#tgs").html(datas.name);
                    $("#deadline").html(datas.deadline);
                    $("#desc").html(datas.desc);

                    let dsn = '';
                    $.each(datas.kelas.dosen, function(index, value) {
                        dsn += `<li>${value.user.name}</li>`;  
                    })
                    $("#dsn").html(dsn);
                },
                error: function(err) {
                    console.log(err);
                    alert('Server Errorr...');
                }
            })
        }
    </script>
@endpush