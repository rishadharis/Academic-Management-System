@extends('layouts.app')

@section('title')
    Kelas {{$kelas->kode_kelas}} / {{$kelas->matkul->name}}
@endsection

@push('css')
    
@endpush

@section('content')
    <div class="card card-primary mb-3">
        <div class="card-body">
            <div class="table-responsive-lg">
                <table class="table table-striped">
                    <tr>
                        <th style="width: 20%">Kode Kelas</th>
                        <td style="width: 5%">:</td>
                        <td>{{$kelas->kode_kelas}}</td>
                    </tr>
                    @foreach ($kelas->dosen as $index => $dsn)
                        <tr>
                            <th style="width: 20%">Dosen Pengampu {{$index + 1}}</th>
                            <td style="width: 5%">:</td>
                            <td>
                               {{$dsn->user->name}} - {{$dsn->user->username}}
                            </td>
                        </tr>
                    @endforeach
                    <tr>
                        <th style="width: 20%">Mata Kuliah</th>
                        <td style="width: 5%">:</td>
                        <td>
                            {{$kelas->matkul->kode_mk}} - {{$kelas->matkul->name}}
                        </td>
                    </tr>
                    <tr>
                        <th style="width: 20%">Jumlah SKS</th>
                        <td style="width: 5%">:</td>
                        <td>
                            {{$kelas->sks}} SKS
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            @foreach ($errors->all() as $error)
                <strong>{{$error}}</strong>
            @endforeach
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
            <a href="javascript:void(0)" id="addMhs" class="btn btn-primary ml-4"><i class="fas fa-plus"></i></a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover" id="table1" style="width: 100%">
                    <thead class="bg-primary">
                        <tr>
                            <th class="text-white">NIM</th>
                            <th class="text-white">Nama</th>
                            <th class="text-white">Email</th>
                            <th class="text-white">#</th>
                        </tr>
                    </thead>
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
                    <h5 class="modal-title" id="exampleModalLabel">Tambah Mahasiswa - Kelas {{$kelas->kode_kelas}} / {{$kelas->matkul->name}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{route('kelas.admin.addMahasiswaToKelas')}}" method="POST">
                    @csrf
                    <input type="hidden" name="kelas_id" id="kelas_id" value="{{$kelas->id}}">
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
                </form>
            </div>
        </div>
    </div>
@endpush

@push('js')
    <script>
        $(document).ready(function() {
            let kelas_id = "{{$kelas->id}}";

            let table1 = $("#table1").DataTable({
                serverSide: true,
                processing: true,
                ajax: `{{url('portal/admin/kelas/${kelas_id}/show')}}`,
                columns: [
                    {data: "username"},
                    {data: "name"},
                    {data: "email"},
                    {data: "action"},
                ],
            });

            let table2 = $("#table2").DataTable({
                serverSide: true,
                processing: true,
                ajax: "{{route('kelas.admin.getMahasiswa')}}",
                columns: [
                    {data: "action"},
                    {data: "username"},
                    {data: "name"},
                    {data: "email"},
                ],
            });

            $("#addMhs").click(function() {
                $("#exampleModal").modal('show');
                table2.draw();
            })

            $(document).on('click', '#remove', function() {
                let id = $(this).data('id');
                swal({
                    title: 'Peringatan !',
                    text: 'Anda yakin mengeluarkan mahasiswa dari kelas?',
                    icon: 'warning',
                    buttons: true,
                    dangerMode: true,
                })
                .then((willDelete) => {
                    if (willDelete) {
                        $.ajax({
                            url: `{{url('portal/admin/kelas/${id}/removeMahasiswaFromKelas')}}`,
                            method: 'DELETE',
                            dataType: 'json',
                            success: function(response) {
                                swal(response.message, {
                                    icon: response.status,
                                });
                                table1.draw()
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