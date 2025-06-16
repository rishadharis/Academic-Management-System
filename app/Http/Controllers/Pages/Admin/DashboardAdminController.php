<?php

namespace App\Http\Controllers\Pages\Admin;

use App\Http\Controllers\Controller;
use App\Models\kelas;
use App\Models\Matkul;
use App\Models\TahunAkademik;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardAdminController extends Controller
{
    public function index()
    {
        $mhsCount = User::role("Mahasiswa")->count();
        $dosenCount = User::role("Dosen")->count();
        $kelasCount = kelas::count();
        $mkCount = Matkul::count();

        return view("pages.admin.dashboard", compact('mhsCount', 'dosenCount', 'kelasCount', 'mkCount'));
    }
}
