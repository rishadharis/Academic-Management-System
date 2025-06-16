<?php

namespace App\Http\Controllers\Pages\Dosen;

use App\Http\Controllers\Controller;
use App\Models\Assigment;
use App\Models\kelas;
use App\Models\KelasDosen;
use App\Models\KelasMahasiswa;
use App\Models\TahunAkademik;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class KelasController extends Controller
{
    public function index()
    {
        $allAkademik = TahunAkademik::select('id', 'name')->orderBy('id', 'DESC')->get();
        if (request()->ajax()) {
            $akademiks_id = request('akademiks_id');

            $kelas = KelasDosen::with(["kelas", "kelas.akademik", "kelas.matkul"])
                ->whereHas('kelas', function ($query) use ($akademiks_id) {
                    $query->where('tahun_akademiks_id', $akademiks_id);
                })
                ->where('users_id', Auth::user()->id)
                ->orderBy('id', 'DESC')
                ->get();

            return DataTables::of($kelas)
                ->addColumn('akademik', function ($row) {
                    return $row->kelas->akademik->name;
                })
                ->addColumn('matkul', function ($row) {
                    return $row->kelas->kode_kelas . ' - ' .  $row->kelas->matkul->name;
                })
                ->addColumn('sks', function ($row) {
                    return $row->kelas->sks;
                })
                ->addColumn('ruangan', function ($row) {
                    return $row->kelas->ruangan ? $row->kelas->ruangan : '-';
                })
                ->addColumn('tgl_uts', function ($row) {
                    return Carbon::parse($row->kelas->tgl_uts)->translatedFormat('d F Y');
                })
                ->addColumn('tgl_uas', function ($row) {
                    return Carbon::parse($row->kelas->tgl_uas)->translatedFormat('d F Y');
                })
                ->addColumn('action', function ($row) {
                    $btn = '<a href="' . route('kelas.dosen.show', ['id' => $row->kelas->id]) . '" class="btn btn-primary btn-sm"><i class="fas fa-eye"></i></a>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->toJson();
        }
        return view("pages.dosen.kelas.index", compact("allAkademik"));
    }

    public function show($id)
    {
        $kelas = kelas::with(["akademik", "matkul", "dosen", "dosen.user"])->find($id);
        if (request()->ajax()) {
            $kelasMahasiswa = KelasMahasiswa::with(["user"])->where('kelas_id', $id)->orderBy('id', 'DESC')->get();
            return DataTables::of($kelasMahasiswa)
                ->addIndexColumn()
                ->addColumn('username', function ($row) {
                    return $row->user->username;
                })
                ->addColumn('name', function ($row) {
                    return $row->user->name;
                })
                ->addColumn('email', function ($row) {
                    return $row->user->email;
                })
                ->rawColumns(['action'])
                ->toJson();
        }
        return view("pages.dosen.kelas.show", compact('kelas'));
    }

    public function getAssigment($id)
    {
        if (request()->ajax()) {
            $assigment = Assigment::where('kelas_id', $id)->orderBy('id', 'DESC')->get();
            return DataTables::of($assigment)
                ->addIndexColumn()
                ->addColumn('name', function ($row) {
                    return $row->name;
                })
                ->addColumn('deadline', function ($row) {
                    return Carbon::parse($row->deadline)->translatedFormat('l, d F Y');
                })
                ->addColumn('action', function ($row) {
                    $btn = '<a href="' . route('kelas.dosen.assigment.show', ['id' => $row->id]) . '" class="btn btn-primary btn-sm mr-3"><i class="fas fa-eye"></i></a>';
                    $btn .= '<a href="' . route('kelas.dosen.assigment.edit', ['id' => $row->id]) . '" class="btn btn-warning btn-sm mr-3"><i class="fas fa-pen"></i></a>';
                    $btn .= '<a href="javascript:void(0)" data-id="' . $row->id . '" id="delete" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></a>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->toJson();
        }
    }
}
