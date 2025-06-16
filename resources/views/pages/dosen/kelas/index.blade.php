@extends('layouts.app')

@section('title', 'Daftar Kelas')

@push('css')
    
@endpush

@section('content')
    <div class="card card-primary">
        <div class="card-body">
            <div class="d-flex justify-content-between">
                <div>
                    <form>
                        <div class="form-group">
                            <select name="tahun_akademiks_id" id="tahun_akademiks_id" class="form-control w-100">
                                @foreach ($allAkademik as $akademik)
                                    <option value="{{$akademik->id}}">{{$akademik->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </form>
                </div>
            </div>
            <div class="table-responsive-lg">
                <table class="table table-bordered table-striped table-hover table-md" id="table" style="width: 100%">
                    <thead class="bg-primary">
                        <tr>
                            <th class="text-white">Tahun Akademik</th>
                            <th class="text-white">Nama Kelas</th>
                            <th class="text-white">SKS</th>
                            <th class="text-white">Ruangan</th>
                            <th class="text-white">Tanggal UTS</th>
                            <th class="text-white">Tanggal UAS</th>
                            <th class="text-white">#</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
@endsection 

@push('js')
    <script>
        $(document).ready(function() {
            let table = $("#table").DataTable({
                serverSide: true,
                processing: true,
                ajax: {
                    url: "{{route('kelas.dosen')}}",
                    method: "GET",
                    data: function(d) {
                        d.akademiks_id = $("#tahun_akademiks_id").val();
                    },
                },
                columns: [
                    {data: "akademik"},
                    {data: "matkul"},
                    {data: "sks"},
                    {data: "ruangan"},
                    {data: "tgl_uts"},
                    {data: "tgl_uas"},
                    {data: "action"},
                ]
            });

            $("#tahun_akademiks_id").change(function() {
                table.draw()
            });
        })
    </script>
@endpush