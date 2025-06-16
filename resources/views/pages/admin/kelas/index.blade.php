@extends('layouts.app')

@section('title', 'Data Kelas')

@push('css')
    
@endpush

@section('content')
    <div class="card card-primary">
        <div class="card-body">
            <div class="d-flex justify-content-between">
                <div>
                    <a href="{{route('kelas.admin.create')}}" class="btn btn-primary"><i class="fas fa-plus"></i> Tambah</a>
                </div>
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
                            <th class="text-white">Dosen Pengampu</th>
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
                    url: "{{route('kelas.admin')}}",
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
                    {data: "dosen"},
                    {data: "action"},
                ]
            });

            $("#tahun_akademiks_id").change(function() {
                table.draw()
            });

            $(document).on('click', '#delete', function() {
                let id = $(this).data('id');
                swal({
                    title: 'Peringatan !',
                    text: 'Anda yakin ingin menghapus data ini?',
                    icon: 'warning',
                    buttons: true,
                    dangerMode: true,
                })
                .then((willDelete) => {
                    if (willDelete) {
                        $.ajax({
                            url: `{{url('portal/admin/kelas/${id}/destroy')}}`,
                            method: 'DELETE',
                            dataType: 'json',
                            success: function(response) {
                                swal(response.message, {
                                    icon: response.status,
                                });
                                table.draw()
                            },
                            error: function(err) {
                                console.log(err);
                                swal('Oppss! Server Error', {
                                    icon: 'error',
                                });
                            }
                        })
                    }
                });
            })
        })
    </script>
@endpush