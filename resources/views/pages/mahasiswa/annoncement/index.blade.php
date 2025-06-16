@extends('layouts.app')

@section('title', 'Pengumuman')

@push('css')

@endpush

@section('content')
    <div class="row mb-5">
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

    <div class="d-flex justify-content-between">
        <div></div>
        <div>
            {{$annoncement->links()}}
        </div>
    </div>
@endsection 

@push('js')
    
@endpush