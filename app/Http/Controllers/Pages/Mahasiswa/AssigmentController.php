<?php

namespace App\Http\Controllers\Pages\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Assigment;
use App\Models\AssigmentUpload;
use App\Models\kelas;
use App\Models\KelasMahasiswa;
use App\Models\TahunAkademik;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AssigmentController extends Controller
{
    public function index(Request $request)
    {
        $filterKelas = $request->kelas_id;
        $akademiks_id = $request->tahun_akademiks_id;

        $latestAkademik = TahunAkademik::latest()->first();
        $allAkademik = TahunAkademik::select(['id', 'name'])->orderBy('id', 'DESC')->get();

        $kelasAssigment = collect();

        $kelasMahasiswa = KelasMahasiswa::with(["kelas", "kelas.matkul"])
            ->where('users_id', Auth::user()->id)
            ->get();

        foreach ($kelasMahasiswa as $kelasMhs) {
            if (!empty($akademiks_id)) {
                $kelasCheck = kelas::with(["dosen", "matkul", "dosen.user", "assigment", "assigment.assigment_upload"])
                    ->where('id', $kelasMhs->kelas_id)
                    ->where('tahun_akademiks_id', $akademiks_id)
                    ->orderBy('id', 'DESC')
                    ->get()
                    ->toArray();
            } else if (!empty($filterKelas)) {
                $kelasCheck = kelas::with(["dosen", "matkul", "dosen.user", "assigment", "assigment.assigment_upload"])
                    ->where('id', $filterKelas)
                    ->orderBy('id', 'DESC')
                    ->get()
                    ->toArray();
            } else {
                $kelasCheck = kelas::with(["dosen", "matkul", "dosen.user", "assigment", "assigment.assigment_upload"])
                    ->where('id', $kelasMhs->kelas_id)
                    ->where('tahun_akademiks_id', $latestAkademik->id)
                    ->orderBy('id', 'DESC')
                    ->get()
                    ->toArray();
            }

            $kelasAssigment = $kelasAssigment->merge($kelasCheck)->unique('id');
        }

        return view("pages.mahasiswa.assigment.index", compact('kelasMahasiswa', 'filterKelas', 'allAkademik', 'akademiks_id', 'kelasAssigment', 'latestAkademik'));
    }

    public function show($id)
    {
        $assigment = Assigment::with(["kelas", "kelas.matkul", "kelas.dosen", "kelas.dosen.user"])->find($id);

        if (!$assigment) {
            return response()->json(['code' => 404, 'status' => 'error', 'message' => 'Data Tugas Tidak Ditemukan.']);
        }

        return response()->json(['code' => 200, 'status' => 'success', 'data' => $assigment]);
    }

    public function store(Request $request)
    {
        $request->validate([
            "file" => "required|mimes:pdf,docx,pptx|max:5048"
        ]);

        $file = $request->file('file');
        $path = 'tugas/';
        $fileName = rand() . '.' . $file->getClientOriginalExtension();
        $file->move($path, $fileName);

        $post = $request->all();
        $post['file'] = $fileName;
        $post['users_id'] = Auth::user()->id;
        $post['status'] = 1;

        AssigmentUpload::create($post);

        return back()->with('success', 'Berhasil mengumpulkan tugas.');
    }

    public function destroy($id)
    {
        $assigmentUpload = AssigmentUpload::find($id);

        if (!$assigmentUpload) {
            return response()->json(['code' => 404, 'status' => 'error', 'message' => 'Data Tidak Ditemukan.']);
        }

        $assigmentUpload->delete();

        return response()->json(['code' => 200, 'status' => 'success', 'message' => 'Data Berhasil Dihapus']);
    }
}
