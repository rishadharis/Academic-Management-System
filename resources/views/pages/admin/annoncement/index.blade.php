@extends('layouts.app')

@section('title', 'Pengumuman')

@push('css')
    <link rel="stylesheet" href="{{asset('')}}modules/summernote/summernote-bs4.css">
@endpush

@section('content')
    <div class="row mb-5" style="max-height: 500px; overflow-y: auto;">
        <div class="col-12">
            <div class="activities">
                @forelse ($annoncement as $item)
                    <div class="activity">
                        <div class="activity-icon bg-primary text-white shadow-primary">
                            <i class="fas fa-comment-alt"></i>
                        </div>
                        <div class="activity-detail">
                            <div class="mb-2">
                                <span class="text-job text-primary">
                                    {{\Carbon\Carbon::parse($item->created_at)->translatedFormat('l, d F Y')}}
                                </span>
                                <span class="bullet"></span>
                                <a class="text-job" href="#">
                                    {{$item->user->name}}
                                </a>
                                <div class="float-right dropdown">
                                    <a href="#" data-toggle="dropdown"><i class="fas fa-ellipsis-h"></i></a>
                                    <div class="dropdown-menu">
                                    <div class="dropdown-title">Pilih</div>
                                    <div class="dropdown-divider"></div>
                                        <a href="javascript:void(0)" onclick="return deleteAnnoncement('{{$item->id}}')" class="dropdown-item has-icon text-danger" ><i class="fas fa-trash-alt"></i> Hapus</a>
                                    </div>
                                </div>
                            </div>
                            <p>
                                {!! $item->desc !!}
                            </p>
                        </div>
                    </div>
                @empty
                    <div class="alert alert-primary w-100">
                        <center>
                            <strong>
                                Belum Ada Pengumuman
                            </strong>
                        </center>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{route('annoncement.admin.store')}}" method="POST">
                @csrf
                <div class="form-group mb-3">
                    <textarea name="desc" id="desc" cols="30" rows="10" class="form-control summernote @error('desc') is-invalid @enderror"></textarea>
                    @error('desc')
                        <span class="invalid-feedback">
                            {{$message}}
                        </span>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary float-right">Kirim</button>
            </form>
        </div>
    </div>
@endsection 

@push('js')
    <script src="{{asset('')}}modules/summernote/summernote-bs4.js"></script>
    <script>
        function deleteAnnoncement(id) {
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
                        url: `{{url('portal/admin/anonncement/${id}/destroy')}}`,
                        method: "DELETE",
                        dataType: "json",
                        success: function(response) {
                            swal(response.message, {
                                icon: response.status,
                            });

                            setTimeout(() => {
                                window.location.reload()
                            }, 2000);
                        },
                        error: function(err) {
                            swal('Poof! Server Errorr...', {
                                icon: 'error',
                            });
                        }
                    })
                } 
            });
        }
    </script>
@endpush