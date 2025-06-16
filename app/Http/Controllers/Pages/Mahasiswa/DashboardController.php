<?php

namespace App\Http\Controllers\Pages\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Assigment;
use App\Models\Jadwal;
use App\Models\kelas;
use App\Models\KelasMahasiswa;
use App\Models\Nilai;
use App\Models\TahunAkademik;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $jadwalTerdekat = [];
        $tugasAll = [];
        $kelasAll = [];
        $akademiks_id = $request->tahun_akademiks_id;

        $allAkademik = TahunAkademik::select(['id', 'name'])->orderBy('id', 'DESC')->get();
        $latestAkademik = TahunAkademik::latest()->first();
        $kelasMahasiswa = KelasMahasiswa::where('users_id', Auth::user()->id)->get();

        foreach ($kelasMahasiswa as $jdwl) {
            if (!empty($request->tahun_akademiks_id)) {
                $dataTerdekat = Jadwal::with(["kelas", "kelas.matkul"])
                    ->where('kelas_id', $jdwl->kelas_id)
                    ->whereHas('kelas', function ($querykelas) use ($akademiks_id) {
                        $querykelas->where('tahun_akademiks_id', $akademiks_id);
                    })
                    ->whereBetween('tanggal', [Carbon::now(), Carbon::now()->addDays(7)])
                    ->orderBy('tanggal', 'asc')
                    ->get()
                    ->toArray();
            } else {
                $dataTerdekat = Jadwal::with(["kelas", "kelas.matkul"])
                    ->where('kelas_id', $jdwl->kelas_id)
                    ->whereHas('kelas', function ($querykelas) use ($latestAkademik) {
                        $querykelas->where('tahun_akademiks_id', $latestAkademik->id);
                    })
                    ->whereBetween('tanggal', [Carbon::now(), Carbon::now()->addDays(7)])
                    ->orderBy('tanggal', 'asc')
                    ->get()
                    ->toArray();
            }

            $jadwalTerdekat = array_merge($jadwalTerdekat, $dataTerdekat);
        }

        foreach ($kelasMahasiswa as $klsMhs) {
            if (!empty($request->tahun_akademiks_id)) {
                $tugasArray = Assigment::with(["kelas", "kelas.matkul", "assigment_upload"])
                    ->where('kelas_id', $klsMhs->kelas_id)
                    ->whereHas('kelas', function ($querykelas) use ($akademiks_id) {
                        $querykelas->where('tahun_akademiks_id', $akademiks_id);
                    })
                    ->orderBy('id', 'desc')
                    ->take(3)
                    ->get()
                    ->toArray();
            } else {
                $tugasArray = Assigment::with(["kelas", "kelas.matkul", "assigment_upload"])
                    ->where('kelas_id', $klsMhs->kelas_id)
                    ->whereHas('kelas', function ($querykelas) use ($latestAkademik) {
                        $querykelas->where('tahun_akademiks_id', $latestAkademik->id);
                    })
                    ->orderBy('id', 'desc')
                    ->take(3)
                    ->get()
                    ->toArray();
            }

            $tugasAll = array_merge($tugasAll, $tugasArray);
        }

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

        return view("pages.mahasiswa.dashboard", compact(
            'jadwalTerdekat',
            'tugasAll',
            'latestAkademik',
            'allAkademik',
            'akademiks_id',
            'kelasAll'
        ));
    }

    public function showKelas($id)
    {
        $kelas = kelas::with(["matkul", "dosen", "dosen.user", "akademik"])->find($id);
        $nilai = Nilai::where('kelas_id', $id)->where('users_id', Auth::user()->id)->first();
        $assigment = Assigment::with(["assigment_upload" => function ($query) {
            $query->where('users_id', Auth::user()->id);
        }])->where('kelas_id', $id)->get();

        $totalNilaiTugas = 0;
        $totalTugas = 0;

        foreach ($assigment as $item) {
            foreach ($item->assigment_upload as $nliTgs) {
                $totalNilaiTugas += $nliTgs->nilai;
                $totalTugas++;
            }
        }

        $rataRataNilaiTugas = $totalTugas > 0 ? $totalNilaiTugas / $totalTugas : 0;

        return view("pages.mahasiswa.kelas.index", compact('kelas', 'nilai', 'rataRataNilaiTugas'));
    }
}
