@extends('layouts.app')

@section('title')
    Detail Tugas Kelas - {{$assigment->kelas->kode_kelas}}/{{$assigment->kelas->matkul->name}} - {{$assigment->name}}
@endsection

@push('css')
@endpush

@section('content')
    <div class="card card-primary mb-3">
        <div class="card-header">
            <a href="{{route('kelas.dosen.show', ['id' => $assigment->kelas->id])}}" class="btn btn-primary"><i class="fas fa-arrow-left"></i> Kembali</a>
        </div>
        <div class="card-body">
            <div class="table-responsive-lg">
                <table class="table table-striped">
                    <tr>
                        <th style="width: 20%">Nama Tugas</th>
                        <td style="width: 5%">:</td>
                        <td>{{$assigment->name}}</td>
                    </tr>
                    <tr>
                        <th style="width: 20%">Deadline</th>
                        <td style="width: 5%">:</td>
                        <td>{{\Carbon\Carbon::parse($assigment->deadline)->translatedFormat('l, d F Y')}}</td>
                    </tr>
                    <tr>
                        <th style="width: 20%">Deskripsi</th>
                        <td style="width: 5%">:</td>
                        <td>{!!$assigment->desc!!}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <strong>
                Daftar Mahasiswa Yang Sudah Mengumpulkan Tugas
            </strong>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover" id="table" style="width: 100%">
                    <thead class="bg-primary">
                        <tr>
                            <th class="text-white">NIM</th>
                            <th class="text-white">Nama</th>
                            <th class="text-white">Tanggal</th>
                            <th class="text-white">Nilai</th>
                            <th class="text-white">#</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
@endsection 

@push('modal')
<div class="modal fade" id="addNilaiModal" tabindex="-1" aria-labelledby="addNilaiModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addNilaiModalLabel">Submit Nilai</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <input type="hidden" name="assigment_uploads_id" id="assigment_uploads_id">
        <div class="form-group mb-3">
            <label for="" class="mb-2">No Induk Mahasiswa</label>
            <input type="text" disabled name="username" id="username" class="form-control">
        </div>
        <div class="form-group mb-3">
            <label for="" class="mb-2">Nama Mahasiswa</label>
            <input type="text" disabled name="name" id="name" class="form-control">
        </div>
        <div class="form-group mb-3">
            <label for="" class="mb-2">Nilai</label>
            <input type="number" name="nilai" id="nilai" class="form-control">
            <span class="text-danger nilai_error"></span>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
        <button type="button" id="submit" class="btn btn-primary">Simpan</button>
      </div>
    </div>
  </div>
</div>
@endpush

@push('js')
    <script>
        $(document).ready(function() {
            let assigments_id = "{{$assigment->id}}";
            let table = $("#table").DataTable({
                serverSide: true,
                processing: true,
                ajax: `{{url('portal/dosen/kelas/assigment/${assigments_id}/show')}}`,
                columns: [
                    {data: "username"},
                    {data: "name"},
                    {data: "created"},
                    {data: "nilai"},
                    {data: "action"},
                ]
            })

            $(document).on('click', '#addNilai', function(e) {
                e.preventDefault();
                let id = $(this).data('id');
                $.ajax({
                    url: `{{url('portal/dosen/kelas/assigment/${id}/showAssigmentUpload')}}`,
                    method: "GET",
                    dataType: "json",
                    success: function(data) {
                        console.log(data);
                        $("#addNilaiModal").modal('show');

                        $("#assigment_uploads_id").val(data.data.id);
                        $("#username").val(data.data.mahasiswa.username);
                        $("#name").val(data.data.mahasiswa.name);
                    }, 
                    error: function(err) {
                        console.log(err);
                        alert('Server Errorr....');
                    }
                })
            })

            $("#submit").click(function(e) {
                e.preventDefault();

                let assigment_uploads_id = $("#assigment_uploads_id").val();
                let nilai = $("#nilai").val();

                $.ajax({
                    url: `{{route('kelas.dosen.saveNilai.show')}}`,
                    method: "POST",
                    data: {assigment_uploads_id: assigment_uploads_id, nilai: nilai},
                    dataType: "json",
                    success: function(response) {
                        console.log(response);
                        if (response.errors) {
                            $.each(response.errors, function(index, value) {
                                $("#" + index).addClass('is-invalid');
                                $(".nilai_error").html(value);

                                setTimeout(() => {
                                   $("#" + index).removeClass('is-invalid');
                                    $(".nilai_error").html(''); 
                                }, 3000);
                            })
                        } else {
                            swal('Berhasil', response.message, 'success');
                            $("#addNilaiModal").modal('hide')
                            table.draw()
                        }
                    },
                    error: function(err) {
                        console.log(err);
                        alert('Server Error...');
                    }
                })
            })
        })
    </script>
@endpush