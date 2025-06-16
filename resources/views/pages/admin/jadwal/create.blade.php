@extends('layouts.app')

@section('title')
    Buat Jadwal - {{$kelas->kode_kelas}}/{{$kelas->matkul->name}}
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
    <div class="card card-primary mb-3">
        <div class="card-header">
            <a href="{{route('jadwal')}}" class="btn btn-primary"><i class="fas fa-arrow-left"></i> Kembali</a>
        </div>
        <div class="card-body">
            <form action="{{route('jadwal.store')}}" method="POST">
                @csrf
                <input type="hidden" name="kelas_id" id="kelas_id" value="{{$kelas->id}}">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <div class="form-group mb-3">
                            <label for="" class="mb-2">Tanggal Mulai Kelas</label>
                            <input type="date" name="tanggal" id="tanggal" class="form-control @error('tanggal') is-invalid @enderror">
                            @error('tanggal')
                                <span class="invalid-feedback">
                                    {{$message}}
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-12 col-sm-12">
                        <div class="form-group mb-3">
                            <label for="" class="mb-2">Jam Mulai</label>
                            <input type="time" name="start_time" id="start_time" class="form-control @error('start_time') is-invalid @enderror">
                            @error('start_time')
                                <span class="invalid-feedback">
                                    {{$message}}
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-12 col-sm-12">
                        <div class="form-group mb-3">
                            <label for="" class="mb-2">Jam Selesai</label>
                            <input type="time" name="end_time" id="end_time" class="form-control @error('end_time') is-invalid @enderror">
                            @error('end_time')
                                <span class="invalid-feedback">
                                    {{$message}}
                                </span>
                            @enderror
                        </div>
                    </div>
                </div>

                <button type="reset" class="btn btn-secondary float-left">Batal</button>
                <button type="submit" class="btn btn-primary float-right">Tambah</button>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <strong>
                Daftar Jadwal Kelas - {{$kelas->kode_kelas}}/{{$kelas->matkul->name}}
            </strong>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover" id="table" style="width: 100%">
                    <thead class="bg-primary">
                        <tr>
                            <th class="text-white">No</th>
                            <th class="text-white">Tanggal</th>
                            <th class="text-white">Jam Mulai</th>
                            <th class="text-white">Jam Selesai</th>
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
            let id = "{{$kelas->id}}";
            let table = $("#table").DataTable({
                serverSide: true,
                processing: true,
                ajax: `{{url('portal/admin/jadwal/${id}/create')}}`,
                columns: [
                    {data: "DT_RowIndex"},
                    {data: "tanggal"},
                    {data: "start_time"},
                    {data: "end_time"},
                    {data: "action"},
                ]
            });

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
                            url: `{{url('portal/admin/jadwal/${id}/destroy')}}`,
                            method: 'DELETE',
                            dataType: 'json',
                            success: function(response) {
                                swal(response.message, {
                                    icon: response.status,
                                });
                                table.draw()
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