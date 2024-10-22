<?php

namespace App\Http\Controllers\Pages\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;

class MahasiswaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (request()->ajax()) {
            $user = User::role('Mahasiswa')->orderBy('id', 'DESC')->get();
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
                ->addColumn('gender', function ($row) {
                    return $row->gender;
                })
                ->addColumn('ttl', function ($row) {
                    return $row->tmp_lahir . ', ' . Carbon::parse($row->tgl_lahir)->translatedFormat('d F Y');
                })
                ->addColumn('telp', function ($row) {
                    return $row->telp;
                })
                ->addColumn('address', function ($row) {
                    return $row->address;
                })
                ->addColumn('action', function ($row) {
                    $btn = '<a href="' . route('mahasiswa.admin.edit', ['id' => $row->id]) . '" class="btn btn-warning btn-sm mr-2"><i class="fas fa-pen"></i></a>';
                    $btn .= '<a href="javascript:void(0)" id="delete" data-id="' . $row->id . '" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></a>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->toJson();
        }
        return view("pages.admin.mahasiswa.index");
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view("pages.admin.mahasiswa.create");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            "username" => "required|unique:users,username",
            "name" => "required",
            "email" => "required|unique:users,username",
            "gender" => "required",
            "tmp_lahir" => "required",
            "tgl_lahir" => "required",
            "telp" => "required",
            "address" => "required",
            "password" => "required|min:8|confirmed",
            "password_confirmation" => "required",
        ]);

        $post = $request->except('password_confirmation');

        $user = User::create($post);
        $user->assignRole('Mahasiswa');

        return back()->with('success', 'Berhasil menyimpan data.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = User::role('Mahasiswa')->find($id);
        return view("pages.admin.mahasiswa.edit", compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = User::role('Mahasiswa')->find($id);

        $request->validate([
            "username" => [
                'required',
                Rule::unique('users', 'username')->ignore($user->id),
            ],
            "name" => "required",
            "email" => [
                'required',
                Rule::unique('users', 'email')->ignore($user->id),
            ],
            "gender" => "required",
            "tmp_lahir" => "required",
            "tgl_lahir" => "required",
            "telp" => "required",
            "address" => "required",
        ]);

        $put = $request->all();

        $user->update($put);

        return back()->with('success', 'Berhasil mengubah data mahasiswa.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::role('Mahasiswa')->find($id);

        if (!$user) {
            return response()->json(['code' => 404, 'status' => 'error', 'message' => 'Data mahasiswa tidak ditemukan.']);
        }

        $user->delete();

        return response()->json(['code' => 200, 'status' => 'success', 'message' => 'Berhasil menghapus data mahasiswa.']);
    }
}
