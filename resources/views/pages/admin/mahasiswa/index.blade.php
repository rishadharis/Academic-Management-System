@extends('layouts.app')

@section('title', 'Data Mahasiswa')

@push('css')
    
@endpush

@section('content')
    <div class="card card-primary">
        <div class="card-header">
            <a href="{{route('mahasiswa.admin.create')}}" class="btn btn-primary"><i class="fas fa-plus"></i> Tambah</a>
        </div>
        <div class="card-body">
            <div class="table-responsive-lg">
                <table class="table table-bordered table-striped table-hover" id="table" style="width: 100%">
                    <thead class="bg-primary">
                        <tr>
                            <th class="text-white">No</th>
                            <th class="text-white">NIM</th>
                            <th class="text-white">Nama Lengkap</th>
                            <th class="text-white">JK</th>
                            <th class="text-white">Email</th>
                            <th class="text-white">Tempat & Tanggal Lahir</th>
                            <th class="text-white">No Handphone</th>
                            <th class="text-white">Alamat</th>
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
                ajax: "{{route('mahasiswa.admin')}}",
                processing: true,
                columns: [
                    {data: "DT_RowIndex"},
                    {data: "username"},
                    {data: "name"},
                    {data: "gender"},
                    {data: "email"},
                    {data: "ttl"},
                    {data: "telp"},
                    {data: "address"},
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
                            url: `{{url('portal/admin/mahasiswa/${id}/destroy')}}`,
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