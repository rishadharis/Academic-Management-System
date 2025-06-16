<?php

namespace App\Http\Controllers\Pages\Admin;

use App\Http\Controllers\Controller;
use App\Models\Annoncement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AnnoncementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $annoncement = Annoncement::with(["user"])->orderBy('id', 'DESC')->get();
        return view("pages.admin.annoncement.index", compact('annoncement'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'desc' => "required"
        ]);

        $post = $request->all();
        $post['users_id'] = Auth::user()->id;

        Annoncement::create($post);

        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $annoncement = Annoncement::find($id);

        if (!$annoncement) {
            return response()->json(['code' => 404, 'status' => 'error', 'message' => 'Data Tidak Ditemukan.']);
        }

        $annoncement->delete();

        return response()->json(['code' => 200, 'status' => 'success', 'message' => 'Berhasil menghapus pengumuman.']);
    }
}
