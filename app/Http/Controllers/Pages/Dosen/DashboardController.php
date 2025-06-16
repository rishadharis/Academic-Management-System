<?php

namespace App\Http\Controllers\Pages\Dosen;

use App\Http\Controllers\Controller;
use App\Models\Jadwal;
use App\Models\kelas;
use App\Models\KelasDosen;
use App\Models\TahunAkademik;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $kelasAll = [];
        $akademiks_id = $request->tahun_akademiks_id;

        $allAkademik = TahunAkademik::select(['id', 'name'])->orderBy('id', 'DESC')->get();
        $latestAkademik = TahunAkademik::latest()->first();

        $kelasMahasiswa = KelasDosen::where('users_id', Auth::user()->id)->get();

        foreach ($kelasMahasiswa as $kls) {
            if (!empty($akademiks_id)) {
                $kelas = kelas::with(["matkul", "dosen", "dosen.user"])
                    ->where('id', $kls->kelas_id)
                    ->where('tahun_akademiks_id', $akademiks_id)
                    ->get()
                    ->toArray();
            } else {
                $kelas = kelas::with(["matkul", "dosen", "dosen.user"])
                    ->where('id', $kls->kelas_id)
                    ->where('tahun_akademiks_id', $latestAkademik->id)
                    ->orderBy('id', 'DESC')
                    ->get()
                    ->toArray();
            }

            $kelasAll = array_merge($kelasAll, $kelas);
        }

        return view("pages.dosen.dashboard", compact('allAkademik', 'kelasAll', 'latestAkademik', 'akademiks_id'));
    }

    public function calendar()
    {
        $jadwal = KelasDosen::where('users_id', Auth::user()->id)->get();

        $jadwalKelas = [];
        $jadwalCalendar = [];

        foreach ($jadwal as $value) {
            $allData = Jadwal::with(["kelas", "kelas.matkul"])
                ->where('kelas_id', $value->kelas_id)
                ->get()
                ->toArray();


            $jadwalKelas = array_merge($jadwalKelas, $allData);
        }

        foreach ($jadwalKelas as $jadwals) {
            $startDateTime = $jadwals['tanggal'] . ' ' . $jadwals['start_time'];
            $endDateTime = $jadwals['tanggal'] . ' ' . $jadwals['end_time'];

            $jadwalCalendar[] = [
                "title" => $jadwals['kelas']['kode_kelas'] . ' - ' . $jadwals['kelas']['matkul']['name'],
                "start" => $startDateTime,
                "end" => $endDateTime,
                "backgroundColor" => "#6777ef",
                "borderColor" => "#6777ef",
                "textColor" => '#fff'
            ];
        }

        return response()->json($jadwalCalendar);
    }
}
