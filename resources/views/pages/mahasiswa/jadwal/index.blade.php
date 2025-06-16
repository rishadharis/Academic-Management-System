@extends('layouts.app')

@section('title', 'Jadwal')

@push('css')
    <link rel="stylesheet" href="{{asset('')}}modules/fullcalendar/fullcalendar.min.css">
@endpush

@section('content')
    <div class="d-flex justify-content-between mb-3">
        <div>
            <form action="">
                <div class="input-group mb-3">
                    <select name="kelas_id" id="kelas_id" class="form-control">
                        <option value="">- Pilih -</option>
                        @foreach ($jadwal as $kls)
                            <option value="{{$kls->kelas->id}}" {{$filterKelas == $kls->kelas->id ? 'selected' : ''}}>{{$kls->kelas->kode_kelas}} - {{$kls->kelas->matkul->name}}</option>
                        @endforeach
                    </select>
                    <div class="input-group-append">
                        <button class="btn btn-outline-primary" type="submit" id="button-addon2"><i class="fas fa-filter"></i></button>
                    </div>
                </div>
            </form>
        </div>
        <div>
            <form action="{{route('jadwal.mahasiswa')}}">
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
    <div class="row">
        <div class="col-lg-7 col-md-12 col-sm-12">
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
        <div class="col-lg-5 col-md-12 col-sm-12">
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
                        @if ($index % 2 == 0 && $index != count($jadwalTerdekat) - 1)
                            <hr class="divide mb-4">
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection 

@push('js')
    <script src="{{asset('')}}modules/fullcalendar/fullcalendar.min.js"></script>
    <script>
        $(document).ready(function() {
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
    </script>
@endpush