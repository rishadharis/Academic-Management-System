<?php

namespace App\Http\Controllers\Pages\Dosen;

use App\Http\Controllers\Controller;
use App\Models\Assigment;
use App\Models\AssigmentUpload;
use App\Models\kelas;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class AssigmentController extends Controller
{
    /**
     * Show the form for creating a new resource.
     */
    public function create($id)
    {
        $kelas = kelas::with(["matkul"])->find($id);
        return view("pages.dosen.assigment.create", compact('kelas'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            "name" => "required",
            "desc" => "required",
            "deadline" => "required"
        ]);

        $post = $request->all();

        Assigment::create($post);

        return redirect()->route('kelas.dosen.show', ['id' => $request->kelas_id])->with('success', 'Berhasil membuat tugas.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $assigment = Assigment::with(["kelas"])->find($id);
        if (request()->ajax()) {
            $assigmentUpload = AssigmentUpload::with(['mahasiswa'])->where('assigments_id', $id)->orderBy('id', 'DESC')->get();
            return DataTables::of($assigmentUpload)
                ->addIndexColumn()
                ->addColumn('username', function ($row) {
                    return $row->mahasiswa->username;
                })
                ->addColumn('name', function ($row) {
                    return $row->mahasiswa->name;
                })
                ->addColumn('created', function ($row) {
                    return Carbon::parse($row->created_at)->translatedFormat('d F Y');
                })
                ->addColumn('nilai', function ($row) {
                    if ($row->nilai != null) {
                        $nilai = $row->nilai;
                    } else {
                        $nilai = '-';
                    }
                    return $nilai;
                })
                ->addColumn('action', function ($row) {
                    if ($row->nilai != null) {
                        $btn = '<a href="' . asset('tugas/' . $row->file) . '" target="_blank" class="btn btn-info btn-sm mr-2"><i class="fas fa-eye"></i></a>';
                        $btn .= '<a href="javascript:void(0)" data-id="' . $row->id . '" id="addNilai" class="btn btn-warning btn-sm"><i class="fas fa-pen"></i></a>';
                    } else {
                        $btn = '<a href="' . asset('tugas/' . $row->file) . '" target="_blank" class="btn btn-info btn-sm mr-2"><i class="fas fa-eye"></i></a>';
                        $btn .= '<a href="javascript:void(0)" data-id="' . $row->id . '" id="addNilai" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i></a>';
                    }
                    return $btn;
                })
                ->rawColumns(['action'])
                ->toJson();
        }
        return view("pages.dosen.assigment.show", compact('assigment'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $assigment = Assigment::with(["kelas"])->find($id);
        return view("pages.dosen.assigment.edit", compact('assigment'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $assigment = Assigment::with(['kelas'])->find($id);

        $request->validate([
            "name" => "required",
            "desc" => "required",
            "deadline" => "required"
        ]);

        $put = $request->all();

        $assigment->update($put);

        return redirect()->route('kelas.dosen.show', ['id' => $assigment->kelas->id])->with('success', 'Berhasil mengubah tugas.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $assigment = Assigment::find($id);

        if (!$assigment) {
            return response()->json(['code' => 404, 'status' => 'error', 'message' => 'Data Tugas Tidak Ditemukan.']);
        }

        $assigment->delete();

        return response()->json(['code' => 200, 'status' => 'success', 'message' => 'Berhasil menghapus tugas.']);
    }

    public function showAssigmentUpload($id)
    {
        $assigmentUpload = AssigmentUpload::with(["mahasiswa"])->find($id);

        if (!$assigmentUpload) {
            return response()->json(['code' => 404, 'status' => 'error', 'message' => 'Data Tidak Ditemukan.']);
        }

        return response()->json(['code' => 200, 'status' => 'success', 'data' => $assigmentUpload]);
    }

    public function saveNilai(Request $request)
    {
        $assigmentUpload = AssigmentUpload::where('id', $request->assigment_uploads_id)->first();

        $validation = Validator::make($request->all(), [
            "nilai" => "required"
        ]);

        if ($validation->fails()) {
            return response()->json(['errors' => $validation->errors()]);
        }

        $assigmentUpload->update([
            'nilai' => $request->nilai
        ]);

        return response()->json(['code' => 200, 'status' => 'success', 'message' => 'Berhasil menyimpan nilai.']);
    }
}
