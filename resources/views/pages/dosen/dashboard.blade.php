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
            <form action="{{route('dashboard.dosen')}}">
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
        <div class="col-lg-6 col-md-6 col-sm-12">
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
        <div class="col-lg-6 col-md-6 col-sm-12">
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
                                            <a href="{{route('kelas.dosen.show', ['id' => $kls['id']])}}" class="btn btn-outline-primary btn-sm">
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
                        url: "{{route('calendar.dosen')}}"
                    }
                ],
            });
        })
    </script>
@endpush