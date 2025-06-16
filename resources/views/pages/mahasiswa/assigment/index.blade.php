@extends('layouts.app')

@section('title', 'Semua Tugas')

@push('css')

@endpush

@section('content')
    <div class="d-flex justify-content-between mb-3">
        <div>
            <form action="">
                <div class="input-group mb-3">
                    <select name="kelas_id" id="kelas_id" class="form-control">
                        <option value="">- Pilih -</option>
                        @foreach ($kelasMahasiswa as $kls)
                            <option value="{{$kls->kelas->id}}" {{$filterKelas == $kls->kelas->id ? 'selected' : ''}}>{{$kls->kelas->kode_kelas}} - {{$kls->kelas->matkul->name}}</option>
                        @endforeach
                    </select>
                    <div class="input-group-append">
                        <button class="btn btn-outline-primary" type="submit" id="button-addon2"><i class="fas fa-filter"></i></button>
                    </div>
                </div>
            </form>
        </div>
        <div>
            <form action="">
                <div class="input-group mb-3">
                    <select name="tahun_akademiks_id" id="tahun_akademiks_id" class="form-control">
                        @foreach ($allAkademik as $akd)
                            <option value="{{$akd->id}}" {{$akademiks_id == $akd->id ? 'selected' : ''}}>{{$akd->name}}</option>
                        @endforeach
                    </select>
                    <div class="input-group-append">
                        <button class="btn btn-outline-primary" type="submit" id="button-addon2"><i class="fas fa-filter"></i></button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            @foreach ($errors->all() as $error)
                {{$error}}
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
        <div class="card-body">
            <div id="accordion">
                <div class="accordion">
                    @forelse ($kelasAssigment as $index => $item)
                        <div class="accordion-header" role="button" data-toggle="collapse" data-target="#panel-body-{{$index}}" aria-expanded="{{$index == 0 ? 'true' : ''}}">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h4 class="mb-3 mt-2">{{$item['kode_kelas']}}</h4>
                                    <h5>{{$item['matkul']['name']}}</h5>
                                    <h4 class="mt-2">{{$item['sks']}} SKS</h4>
                                </div>
                                <div>
                                    <h4 class="mb-3 mt-2">Dosen Pengampu</h4>
                                    <ul>
                                        @foreach ($item['dosen'] as $dsn)
                                            <li>{{$dsn['user']['name']}}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="accordion-body collapse {{$index == 0 ? 'show' : ''}}" id="panel-body-{{$index}}" data-parent="#accordion">
                            <div class="table-responsive-lg">
                                <table class="table table-bordered table-striped table-hover table-sm">
                                    <thead class="bg-primary">
                                        <tr>
                                            <th class="text-white">Nama Tugas</th>
                                            <th class="text-white">Deadline</th>
                                            <th class="text-white">Nilai</th>
                                            <th class="text-white">Status</th>
                                            <th class="text-white">File</th>
                                            <th class="text-white">#</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($item['assigment'] as $asg)
                                            <tr>
                                                <td>{{$asg['name']}}</td>
                                                <td>{{\Carbon\Carbon::parse($asg['deadline'])->translatedFormat('d F Y')}}</td>
                                                <td>
                                                    @php
                                                        $isDone = false;
                                                        $nilai = null;
                                                        foreach ($asg['assigment_upload'] as $asgUp) {
                                                            if ($asgUp['assigments_id'] == $asg['id']) {
                                                                $isDone = true;
                                                                $nilai = $asgUp['nilai'];
                                                                break;
                                                            }
                                                        }
                                                    @endphp
                                                    @if ($isDone)
                                                        {{$nilai}}
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                                <td>
                                                    @if (\Carbon\Carbon::parse($asg['deadline'])->isFuture())
                                                        @php
                                                            $isDone = false;
                                                            foreach ($asg['assigment_upload'] as $asgUp) {
                                                                if ($asgUp['assigments_id'] == $asg['id']) {
                                                                    $isDone = true;
                                                                    break;
                                                                }
                                                            }
                                                        @endphp

                                                        @if ($isDone)
                                                            <span class="badge bg-primary text-white p-2">
                                                                Done
                                                            </span>
                                                        @else
                                                            <span class="badge bg-primary text-white p-2">
                                                                Open
                                                            </span>
                                                        @endif
                                                    @else 
                                                        <span class="badge bg-danger text-white p-2">
                                                            Expired
                                                        </span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @php
                                                        $isDone = false;
                                                        foreach ($asg['assigment_upload'] as $asgUp) {
                                                            if ($asgUp['assigments_id'] == $asg['id']) {
                                                                $isDone = true;
                                                                $file = $asgUp['file'];
                                                                break;
                                                            }
                                                        }
                                                    @endphp
                                                    @if ($isDone)
                                                        <span class="badge bg-primary text-white p-2">
                                                            <a class="text-white" href="{{asset('tugas/' . $file)}}" target="_blank">
                                                                {{$file}}
                                                            </a>
                                                        </span>
                                                    @else
                                                        <span class="badge bg-primary text-white p-2">
                                                            Belum Upload Tugas
                                                        </span>
                                                    @endif
                                                    
                                                </td>
                                                <td>
                                                    @if (\Carbon\Carbon::parse($asg['deadline'])->isFuture())
                                                        @php
                                                            $isDone = false;
                                                            foreach ($asg['assigment_upload'] as $asgUp) {
                                                                if ($asgUp['assigments_id'] == $asg['id']) {
                                                                    $isDone = true;
                                                                    $ids = $asgUp['id'];
                                                                    break;
                                                                }
                                                            }
                                                        @endphp
                                                        @if ($isDone)
                                                            <a href="javscript:void(0)" onclick="return deleteUpload('{{$ids}}')" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></a>
                                                        @else
                                                            <a href="javscript:void(0)" onclick="return uploadTugas('{{$asg['id']}}')" class="btn btn-primary btn-sm"><i class="fas fa-upload"></i></a>
                                                        @endif
                                                    @else
                                                        <a href="javscript:void(0)" class="btn btn-danger btn-sm disabled"><i class="fas fa-upload"></i></a>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6">Belum Ada Tugas</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @empty
                        <div class="alert alert-primary text-center">
                            <strong>
                                @if (!empty($akademiks_id))
                                    @php
                                        $filteredAkademik = $allAkademik->where('id', $akademiks_id)->first();
                                    @endphp
                                    Tidak Ada Data Kelas & Tugas Di Tahun Ajaran Ini - {{$filteredAkademik->name}}
                                @else 
                                    Belum Ada Kelas Di Tahun Ajaran - {{$latestAkademik ? $latestAkademik->name : 'Belum Ada Tahun Ajaran'}}
                                @endif
                            </strong>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection 

@push('modal')
    <div class="modal fade" id="tugasModal" tabindex="-1" aria-labelledby="tugasModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="tugasModalLabel">Upload Tugas</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{route('assigment.mahasiswa.store')}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" name="assigments_id" id="assigments_id">
                        <div class="row">
                            <div class="col-lg-3 col-md-6 col-sm-12">
                                Kelas
                                <h6 id="kls">Nama Kelas</h6>
                            </div>
                            <div class="col-lg-3 col-md-6 col-sm-12">
                                Tugas
                                <h6 id="tgs">Nama Tugas</h6>
                            </div>
                            <div class="col-lg-3 col-md-6 col-sm-12">
                                Deadline
                                <h6 id="deadline">Nama Tugas</h6>
                            </div>
                            <div class="col-lg-3 col-md-6 col-sm-12">
                                Dosen Pengampu
                                <ul id="dsn">
                                    
                                </ul>
                            </div>
                            <div class="col-lg-12 col-md-12 col-sm-12">
                                Deskripsi
                                <p id="desc">
                                    Lorem ipsum dolor sit amet consectetur adipisicing elit. Dolore quis alias deleniti nisi tempora doloribus excepturi voluptatem suscipit quaerat autem iure deserunt iste ducimus voluptatum consectetur, atque quae! Doloremque, numquam.
                                </p>
                            </div>
                            <div class="col-lg-12 col-md-12 col-sm-12">
                                <div class="form-group">
                                    <label for="">Upload File</label>
                                    <input type="file" name="file" id="file" class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Upload</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endpush

@push('js')
    <script>
        function uploadTugas(id) {
            $.ajax({
                url: `{{url('portal/mahasiswa/assigment/${id}/show')}}`,
                method: "GET",
                dataType: "json",
                success: function(data) {
                    $("#tugasModal").modal('show')
                    let datas = data.data;
                    $("#assigments_id").val(datas.id);
                    $("#kls").html(datas.kelas.kode_kelas + ' - ' + datas.kelas.matkul.name);
                    $("#tgs").html(datas.name);
                    $("#deadline").html(datas.deadline);
                    $("#desc").html(datas.desc);

                    let dsn = '';
                    $.each(datas.kelas.dosen, function(index, value) {
                        dsn += `<li>${value.user.name}</li>`;  
                    })
                    $("#dsn").html(dsn);
                },
                error: function(err) {
                    console.log(err);
                    alert('Server Errorr...');
                }
            })
        }

        function deleteUpload(id) {
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
                        url: `{{url('portal/mahasiswa/assigment/${id}/destroy')}}`,
                        method: "DELETE",
                        dataType: 'json',
                        success: function(response) {
                            swal(response.message, {
                                icon: response.status,
                            });

                            setTimeout(() => {
                                window.location.reload()
                            }, 3000);
                        },
                        error: function(err) {
                            alert('Server Errorr...');
                        }
                    })
                }
            });
        }
    </script>
@endpush