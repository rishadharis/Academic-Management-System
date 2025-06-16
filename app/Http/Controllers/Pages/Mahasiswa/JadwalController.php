<?php

namespace App\Http\Controllers\Pages\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Jadwal;
use App\Models\KelasMahasiswa;
use App\Models\TahunAkademik;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JadwalController extends Controller
{
    public function index(Request $request)
    {
        $filterKelas = $request->kelas_id;
        $akademiks_id = $request->tahun_akademiks_id;

        $latestAkademik = TahunAkademik::latest()->first();
        $allAkademik = TahunAkademik::select(['id', 'name'])->orderBy('id', 'DESC')->get();

        $jadwal = KelasMahasiswa::with(["kelas", "kelas.matkul"])
            ->where('users_id', Auth::user()->id)
            ->get();

        $jadwalTerdekat = collect();

        foreach ($jadwal as $value) {
            if (!empty($filterKelas)) {
                $dataTerdekat = Jadwal::with(["kelas", "kelas.matkul"])
                    ->where('kelas_id', $filterKelas)
                    ->whereBetween('tanggal', [Carbon::now(), Carbon::now()->addDays(7)])
                    ->orderBy('tanggal', 'asc')
                    ->get();
            } else if (!empty($akademiks_id)) {
                $dataTerdekat = Jadwal::with(["kelas", "kelas.matkul"])
                    ->whereHas('kelas', function ($queryAkademik) use ($akademiks_id) {
                        $queryAkademik->where('tahun_akademiks_id', $akademiks_id);
                    })
                    ->whereBetween('tanggal', [Carbon::now(), Carbon::now()->addDays(7)])
                    ->orderBy('tanggal', 'asc')
                    ->get();
            } else {
                $dataTerdekat = Jadwal::with(["kelas", "kelas.matkul"])
                    ->where('kelas_id', $value->kelas_id)
                    ->whereHas('kelas', function ($queryAkademik) use ($latestAkademik) {
                        $queryAkademik->where('tahun_akademiks_id', $latestAkademik->id);
                    })
                    ->whereBetween('tanggal', [Carbon::now(), Carbon::now()->addDays(7)])
                    ->orderBy('tanggal', 'asc')
                    ->get();
            }

            $jadwalTerdekat = $jadwalTerdekat->merge($dataTerdekat)->unique('id');
        }


        return view("pages.mahasiswa.jadwal.index", compact('jadwalTerdekat', 'jadwal', 'filterKelas', 'allAkademik', 'akademiks_id'));
    }

    public function calendar()
    {
        $jadwal = KelasMahasiswa::where('users_id', Auth::user()->id)->get();

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
