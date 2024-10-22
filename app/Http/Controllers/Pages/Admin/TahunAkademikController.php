<?php

namespace App\Http\Controllers\Pages\Admin;

use App\Http\Controllers\Controller;
use App\Models\TahunAkademik;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class TahunAkademikController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (request()->ajax()) {
            $akademik = TahunAkademik::orderBy('id', 'DESC')->get();
            return DataTables::of($akademik)
                ->addIndexColumn()
                ->addColumn('name', function ($row) {
                    return $row->name;
                })
                ->addColumn('action', function ($row) {
                    $btn = '<a href="' . route('tahun.akademik.admin.edit', ['id' => $row->id]) . '" class="btn btn-warning btn-sm mr-2"><i class="fas fa-pen"></i></a>';
                    $btn .= '<a href="javascript:void(0)" id="delete" data-id="' . $row->id . '" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></a>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->toJson();
        }
        return view("pages.admin.akademik.index");
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view("pages.admin.akademik.create");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            "name" => "required"
        ]);

        $post = $request->all();

        TahunAkademik::create($post);

        return back()->with('success', 'Berhasil menyimpan data.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $akademik = TahunAkademik::find($id);
        return view("pages.admin.akademik.edit", compact('akademik'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $akademik = TahunAkademik::find($id);

        $request->validate([
            "name" => "required"
        ]);

        $put = $request->all();

        $akademik->update($put);

        return back()->with('success', 'Berhasil mengubah data.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $akademik = TahunAkademik::find($id);

        if (!$akademik) {
            return response()->json(['code' => 404, 'status' => 'error', 'message' => 'Data Tahun Akademik Tidak Ditemukan.']);
        }

        $akademik->delete();

        return response()->json(['code' => 200, 'status' => 'success', 'message' => 'Berhasil Menghapus Data Tahun Akademik.']);
    }
}
