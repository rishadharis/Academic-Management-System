<?php

namespace App\Http\Controllers\Pages\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;

class DosenController extends Controller
{
    public function index()
    {
        if (request()->ajax()) {
            $user = User::role('Dosen')->orderBy('id', 'DESC')->get();
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
                    $btn = '<a href="' . route('dosen.admin.show', ['id' => $row->id]) . '" class="btn btn-warning btn-sm mr-2"><i class="fas fa-pen"></i></a>';
                    $btn .= '<a href="javascript:void(0)" id="delete" data-id="' . $row->id . '" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></a>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->toJson();
        }
        return view("pages.admin.dosen.index");
    }

    public function create()
    {
        return view("pages.admin.dosen.create");
    }

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
        $user->assignRole('Dosen');

        return back()->with('success', 'Berhasil menyimpan data.');
    }

    public function show($id)
    {
        $user = User::role('Dosen')->find($id);
        return view("pages.admin.dosen.edit", compact('user'));
    }

    public function update(Request $request, $id)
    {
        $user = User::role('Dosen')->find($id);

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

        return back()->with('success', 'Berhasil mengubah data.');
    }

    public function destroy($id)
    {
        $user = User::role('Dosen')->find($id);

        if (!$user) {
            return response()->json(['code' => 404, 'status' => 'error', 'message' => 'Data Dosen Tidak Ditemukan.']);
        }

        $user->delete();

        return response()->json(['code' => 200, 'status' => 'success', 'message' => 'Berhasil Menghapus Data Dosen.']);
    }
}
