<?php

namespace App\Http\Controllers\Pages\Dosen;

use App\Http\Controllers\Controller;
use App\Models\kelas;
use App\Models\KelasDosen;
use App\Models\KelasMahasiswa;
use App\Models\Nilai;
use App\Models\TahunAkademik;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class PenilaianController extends Controller
{
    /**
     * Display a listing of the resource.
     */
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
                    $btn = '<a href="' . route('penilaian.dosen.show', ['id' => $row->kelas->id]) . '" class="btn btn-primary btn-sm"><i class="fas fa-eye"></i></a>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->toJson();
        }

        return view("pages.dosen.penilaian.index", compact('allAkademik'));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $kelas = kelas::with(["matkul", "akademik"])->find($id);
        $kelasMahasiswa = KelasMahasiswa::with(['user', 'user.nilai' => function ($query) use ($id) {
            $query->where('kelas_id', $id);
        }])
            ->where('kelas_id', $id)
            ->orderBy('id', 'DESC')
            ->get();

        return view("pages.dosen.penilaian.show", compact('kelas', 'kelasMahasiswa'));
    }

    /**
     * Store the specified resource.
     */
    public function store(Request $request)
    {
        $request->validate([
            "nilai_uts" => "required|array",
            "nilai_uts.*" => "required",
            "nilai_uas" => "required|array",
            "nilai_uas.*" => "required",
        ]);

        foreach ($request->users_id as $index => $value) {
            Nilai::updateOrCreate(
                [
                    'kelas_id' => $request->kelas_id,
                    'users_id' => $value,
                ],
                [
                    'nilai_uts' => $request->nilai_uts[$index],
                    'nilai_uas' => $request->nilai_uas[$index]
                ]
            );
        }

        return back()->with('success', 'Berhasil menyimpan data nilai.');
    }
}
