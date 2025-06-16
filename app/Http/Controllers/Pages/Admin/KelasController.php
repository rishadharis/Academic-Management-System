<?php

namespace App\Http\Controllers\Pages\Admin;

use App\Http\Controllers\Controller;
use App\Models\kelas;
use App\Models\KelasDosen;
use App\Models\KelasMahasiswa;
use App\Models\Matkul;
use App\Models\TahunAkademik;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;

class KelasController extends Controller
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
                    $btn = '<div class="dropdown d-inline mr-2">
                                <button class="btn btn-primary dropdown-toggle btn-sm" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Pilih
                                </button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="' . route('kelas.admin.show', ['id' => $row->id]) . '">Detail</a>
                                    <a class="dropdown-item" href="' . route('kelas.admin.edit', ['id' => $row->id]) . '">Edit</a>
                                    <a class="dropdown-item" href="javascript:void(0)" data-id="' . $row->id . '" id="delete">Hapus</a>
                                </div>
                            </div>';
                    return $btn;
                })
                ->rawColumns(['dosen', 'action'])
                ->toJson();
        }
        return view("pages.admin.kelas.index", compact('allAkademik'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $akademik = TahunAkademik::orderBy('id', 'DESC')->get();
        $dosen = User::role("Dosen")->orderBy('name', 'DESC')->get();
        $matkul = Matkul::orderBy('id', 'DESC')->get();
        return view("pages.admin.kelas.create", compact('akademik', 'dosen', 'matkul'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            "matkuls_id" => "required",
            "tahun_akademiks_id" => "required",
            "kode_kelas" => "required|unique:kelas,kode_kelas",
            "sks" => "required",
            "tgl_uts" => "required",
            "tgl_uas" => "required",
            "desc" => "required",
            "users_id" => "required",
        ]);

        $postKelas = $request->except('users_id');

        $kelas = kelas::create($postKelas);

        foreach ($request->users_id as $value) {
            KelasDosen::create([
                'kelas_id' => $kelas->id,
                'users_id' => $value,
            ]);
        }

        return back()->with('success', 'Berhasim menyimpan data.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
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
                ->addColumn('action', function ($row) {
                    $btn = '<a href="javascript:void(0)" class="btn btn-danger btn-sm" data-id="' . $row->id . '" id="remove"><i class="fas fa-trash"></i></a>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->toJson();
        }
        return view("pages.admin.kelas.show", compact('kelas'));
    }

    public function getMahasiswa()
    {
        if (request()->ajax()) {
            $user = User::role('Mahasiswa')->orderBy('name', 'DESC')->get();
            return DataTables::of($user)
                ->addIndexColumn()
                ->addColumn('username', function ($row) {
                    return $row->username;
                })
                ->addColumn('name', function ($row) {
                    return $row->name;
                })
                ->addColumn('email', function ($row) {
                    return $row->email;
                })
                ->addColumn('action', function ($row) {
                    $checkBox = '<input type="checkbox" name="users_id[]" id="users_id" value="' . $row->id . '">';
                    return $checkBox;
                })
                ->rawColumns(['action'])
                ->toJson();
        }
    }

    public function addMahasiswaToKelas(Request $request)
    {
        $request->validate([
            "users_id" => "required"
        ], [
            "users_id.required" => "Pilih mahasiswa yang akan di masukan ke kelas"
        ]);

        foreach ($request->users_id as $value) {
            KelasMahasiswa::create([
                'kelas_id' => $request->kelas_id,
                'users_id' => $value
            ]);
        }

        return back()->with('success', 'Berhasil menambahkan mahasiswa ke dalam kelas.');
    }

    public function removeMahasiswaFromKelas($id)
    {
        $kelasMahasiswa = KelasMahasiswa::find($id);

        if (!$kelasMahasiswa) {
            return response()->json(['code' => 404, 'status' => 'error', 'message' => 'Data Mahasiswa Tidak Ditemukan di dalam kelas.']);
        }

        $kelasMahasiswa->delete();

        return response()->json(['code' => 200, 'status' => 'success', 'message' => 'Berhasil mengeluarkan mahasiswa dari kelas.']);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $akademik = TahunAkademik::orderBy('id', 'DESC')->get();
        $dosen = User::role("Dosen")->orderBy('name', 'DESC')->get();
        $matkul = Matkul::orderBy('id', 'DESC')->get();

        $kelas = kelas::with(["matkul", "dosen"])->find($id);
        return view("pages.admin.kelas.edit", compact('akademik', 'dosen', 'matkul', 'kelas'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $kelas = Kelas::find($id);

        $request->validate([
            "matkuls_id" => "required",
            "tahun_akademiks_id" => "required",
            "kode_kelas" => [
                "required",
                Rule::unique('kelas', 'kode_kelas')->ignore($kelas->id),
            ],
            "sks" => "required",
            "tgl_uts" => "required",
            "tgl_uas" => "required",
            "desc" => "required",
            "users_id" => "required",
        ]);

        $selectedDosen = $request->input('users_id', []);

        $currentDosen = $kelas->dosen()->pluck('id')->toArray();

        $dosenToRemove = array_diff($currentDosen, $selectedDosen);
        if (!empty($dosenToRemove)) {
            foreach ($dosenToRemove as $dosenId) {
                $kelas->dosen()->where('id', $dosenId)->delete();
            }
        }

        $dosenToAdd = array_diff($selectedDosen, $currentDosen);
        foreach ($dosenToAdd as $dosenId) {
            $kelas->dosen()->create(['users_id' => $dosenId]);
        }

        $put = $request->except('users_id');
        $kelas->update($put);

        return back()->with('message', 'Berhasil mengubah data kelas.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $kelas = kelas::find($id);

        if (!$kelas) {
            return response()->json(['code' => 404, 'status' => 'error', 'message' => 'Data Kelas Tidak Ditemukan.']);
        }

        $kelas->delete();

        return response()->json(['code' => 200, 'status' => 'success', 'message' => 'Berhasil menghapus data kelasa.']);
    }
}
