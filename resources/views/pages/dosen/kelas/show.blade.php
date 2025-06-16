@extends('layouts.app')

@section('title')
    Kelas {{$kelas->kode_kelas}} / {{$kelas->matkul->name}}
@endsection

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
    <div class="row mb-3">
        <div class="col-lg-5 col-md-6 col-sm-12">
            <div class="card card-primary">
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
                            @foreach ($kelas->dosen as $index => $dsn)
                                <tr>
                                    <th>Dosen Pengampu {{$index + 1}}</th>
                                    <td style="width: 5%">:</td>
                                    <td>
                                       {{$dsn->user->name}} - {{$dsn->user->username}}
                                    </td>
                                </tr>
                            @endforeach
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
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-7 col-md-6 col-sm-12">
            <div class="card">
                <div class="card-header">
                    <strong>
                        Daftar Mahasiswa Kelas {{$kelas->kode_kelas}} / {{$kelas->matkul->name}}
                    </strong>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover" id="table" style="width: 100%">
                            <thead class="bg-primary">
                                <tr>
                                    <th class="text-white">NIM</th>
                                    <th class="text-white">Nama</th>
                                    <th class="text-white">Email</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between mb-3">
                <div>
                    <strong>
                        Tugas Kelas {{$kelas->kode_kelas}} / {{$kelas->matkul->name}}
                    </strong>
                </div>
                <div>
                    <a href="{{route('kelas.dosen.assigment', ['id' => $kelas->id])}}" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Buat Tugas</a>
                </div>
            </div>

            <div class="table-responsive-lg">
                <table class="table table-bordered table-striped table-hover" id="table2" style="width: 100%">
                    <thead class="bg-primary">
                        <tr>
                            <th class="text-white">No</th>
                            <th class="text-white">Nama Tugas</th>
                            <th class="text-white">Deadline</th>
                            <th class="text-white">#</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
@endsection 

@push('modal')
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Buat Tugas</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive-lg">
                        <table class="table table-bordered table-striped table-hover table-sm" id="table2" style="width: 100%">
                            <thead class="bg-primary">
                                <tr>
                                    <th class="text-white">#</th>
                                    <th class="text-white">NIM</th>
                                    <th class="text-white">Nama Lengkap</th>
                                    <th class="text-white">Email</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Tambah</button>
                </div>
            </div>
        </div>
    </div>
@endpush

@push('js')
    <script>
        $(document).ready(function() {
            let kelas_id = "{{$kelas->id}}";
            let table = $("#table").DataTable({
                serverSide: true,
                processing: true,
                lengthMenu: [ [5, 15, 25, 50, 100], [5, 15, 25, 50, 100] ],
                ajax: `{{url('portal/dosen/kelas/${kelas_id}/show')}}`,
                columns: [
                    {data: "username"},
                    {data: "name"},
                    {data: "email"},
                ],
            });

            let table2 = $("#table2").DataTable({
                serverSide: true,
                processing: true,
                lengthMenu: [ [10, 25, 50, 100], [10, 25, 50, 100] ],
                ajax: `{{url('portal/dosen/kelas/${kelas_id}/getAssigment')}}`,
                columns: [
                    {data: "DT_RowIndex"},
                    {data: "name"},
                    {data: "deadline"},
                    {data: "action"},
                ],
            })

            $(document).on('click', '#delete', function(e) {
                e.preventDefault();
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
                            url: `{{url('portal/dosen/kelas/assigment/${id}/destroy')}}`,
                            method: "DELETE",
                            dataType: "json",
                            success: function(response) {
                                swal(response.message, {
                                    icon: response.status,
                                });

                                table2.draw()
                            },
                            error: function(err) {
                                swal('Poof! Server Error....', {
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