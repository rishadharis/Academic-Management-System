<?php

namespace App\Http\Controllers\Pages\Admin;

use App\Http\Controllers\Controller;
use App\Models\Matkul;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;

class MatkulController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (request()->ajax()) {
            $matkul = Matkul::orderBy('id', 'DESC')->get();
            return DataTables::of($matkul)
                ->addIndexColumn()
                ->addColumn('code', function ($row) {
                    return $row->kode_mk;
                })
                ->addColumn('name', function ($row) {
                    return $row->name;
                })
                ->addColumn('desc', function ($row) {
                    return $row->desc;
                })
                ->addColumn('action', function ($row) {
                    $btn = '<a href="' . route('matkul.admin.edit', ['id' => $row->id]) . '" class="btn btn-warning btn-sm mr-2"><i class="fas fa-pen"></i></a>';
                    $btn .= '<a href="javascript:void(0)" id="delete" data-id="' . $row->id . '" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></a>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->toJson();
        }
        return view("pages.admin.matkul.index");
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view("pages.admin.matkul.create");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            "kode_mk" => "required|unique:matkuls,kode_mk",
            "name" => "required",
            "desc" => "required"
        ]);

        $post = $request->all();

        Matkul::create($post);

        return back()->with('success', 'Berhasil menyimpan data.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $matkul = Matkul::find($id);
        return view("pages.admin.matkul.edit", compact('matkul'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $matkul = Matkul::find($id);

        $request->validate([
            "kode_mk" => [
                Rule::unique('matkuls', 'kode_mk')->ignore($matkul->id),
            ],
            "name" => "required",
            "desc" => "required"
        ]);

        $put = $request->all();

        $matkul->update($put);

        return back()->with('success', 'Berhasil mengubah data.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $matkul = Matkul::find($id);

        if (!$matkul) {
            return response()->json(['code' => 404, 'status' => 'error', 'message' => 'Data Mata Kuliah Tidak Ditemukan.']);
        }

        $matkul->delete();

        return response()->json(['code' => 200, 'status' => 'success', 'message' => 'Berhasil Menghapus Data Mata Kuliah.']);
    }
}
