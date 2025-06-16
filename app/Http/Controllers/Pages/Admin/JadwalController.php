<?php

namespace App\Http\Controllers\Pages\Admin;

use App\Http\Controllers\Controller;
use App\Models\Jadwal;
use App\Models\kelas;
use App\Models\TahunAkademik;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class JadwalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $allAkademik = TahunAkademik::select('id', 'name')->orderBy('id', 'DESC')->get();

        if (request()->ajax()) {
            $akademiks_id = request('akademiks_id');

            if (!empty($akademiks_id)) {
                $kelas = kelas::with(["akademik", "matkul", "dosen", "dosen.user"])->where('tahun_akademiks_id', $akademiks_id)->orderBy('id', 'DESC')->get();
            } else {
                $kelas = kelas::with(["akademik", "matkul", "dosen", "dosen.user"])->where('tahun_akademiks_id', $akademiks_id)->orderBy('id', 'DESC')->get();
            }

            return DataTables::of($kelas)
                ->addColumn('akademik', function ($row) {
                    return $row->akademik->name;
                })
                ->addColumn('matkul', function ($row) {
                    return $row->kode_kelas . ' - ' .  $row->matkul->name;
                })
                ->addColumn('sks', function ($row) {
                    return $row->sks;
                })
                ->addColumn('ruangan', function ($row) {
                    return $row->ruangan ? $row->ruangan : '-';
                })
                ->addColumn('tgl_uts', function ($row) {
                    return Carbon::parse($row->tgl_uts)->translatedFormat('d F Y');
                })
                ->addColumn('tgl_uas', function ($row) {
                    return Carbon::parse($row->tgl_uas)->translatedFormat('d F Y');
                })
                ->addColumn('dosen', function ($row) {
                    $dsn = '<ul>';
                    foreach ($row->dosen as $value) {
                        $dsn .= '<li>
                                    ' . $value->user->name . '
                                </li>';
                    }
                    $dsn .= '</ul>';
                    return $dsn;
                })
                ->addColumn('action', function ($row) {
                    $btn = '<a href="' . route('jadwal.create', ['id' => $row->id]) . '" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i></a>';
                    return $btn;
                })
                ->rawColumns(['dosen', 'action'])
                ->toJson();
        }
        return view("pages.admin.jadwal.index", compact('allAkademik'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($id)
    {
        $kelas = kelas::with(["matkul"])->find($id);

        if (request()->ajax()) {
            $jadwal = Jadwal::where('kelas_id', $id)->orderBy('id', 'DESC')->get();
            return DataTables::of($jadwal)
                ->addIndexColumn()
                ->addColumn('tanggal', function ($row) {
                    return Carbon::parse($row->tanggal)->translatedFormat('d F Y');
                })
                ->addColumn('start_time', function ($row) {
                    return $row->start_time;
                })
                ->addColumn('end_time', function ($row) {
                    return $row->end_time;
                })
                ->addColumn('action', function ($row) {
                    $btn = '<a href="javascript:void(0)" id="delete" data-id="' . $row->id . '" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></a>';
                    return $btn;
                })
                ->toJson();
        }

        return view("pages.admin.jadwal.create", compact('kelas'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            "tanggal" => "required",
            "start_time" => "required",
            "end_time" => "required"
        ]);

        $post = $request->all();

        Jadwal::create($post);

        return back()->with('success', 'Berhasil menyimpan data.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $jadwal = Jadwal::find($id);

        if (!$jadwal) {
            return response()->json(['code' => 404, 'status' => 'error', 'message' => 'Data Jadwal Tidak Ditemukan.']);
        }

        $jadwal->delete();

        return response()->json(['code' => 200, 'status' => 'success', 'message' => 'Berhasil menghapus data jadwal.']);
    }
}
