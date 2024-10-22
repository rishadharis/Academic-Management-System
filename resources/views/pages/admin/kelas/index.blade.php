@extends('layouts.app')

@section('title', 'Data Kelas')

@push('css')
    
@endpush

@section('content')
    <div class="card card-primary">
        <div class="card-header">
            <a href="{{route('kelas.admin.create')}}" class="btn btn-primary"><i class="fas fa-plus"></i> Tambah</a>
        </div>
        <div class="card-body">
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
                ajax: "{{route('kelas.admin')}}",
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