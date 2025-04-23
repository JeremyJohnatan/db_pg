<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDF;
use Carbon\Carbon;
use App\Models\AnalisisProduk; // Pastikan Anda mengimpor model AnalisisProduk

class LaporanController extends Controller
{
    /**
     * Menampilkan halaman utama laporan di dashboard.
     *
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
     */
    public function indexDashboard()
    {
        // Ambil data laporan analisis produk
        $laporanProduk = AnalisisProduk::select(
            'tanggal as tanggal_awal',
            DB::raw("DATE_ADD(tanggal, INTERVAL 6 DAY) as tanggal_akhir"), // Contoh perkiraan tanggal akhir (mingguan)
            DB::raw("'Analisis Produk' as jenis_laporan"),
            'created_at as tanggal_dibuat',
            DB::raw("'Selesai' as status") // Asumsi status default
        )
        ->distinct()
        ->orderByDesc('created_at')
        ->get();

        // Ambil data laporan analisis pabrik
        $laporanPabrik = DB::table('analisis_pabrik')
            ->select(
                'tanggal_awal',
                'tanggal_akhir',
                DB::raw("'Analisis Pabrik' as jenis_laporan"),
                'created_at as tanggal_dibuat',
                DB::raw("'Selesai' as status") // Asumsi status default
            )
            ->distinct()
            ->orderByDesc('created_at')
            ->get();

        // Gabungkan kedua jenis laporan
        $daftarLaporan = $laporanProduk->concat($laporanPabrik)->sortByDesc('tanggal_dibuat');

        // Kirim data ke view 'dashboard.laporan.index'
        return view('dashboard.laporan.index', compact('daftarLaporan'));
    }

    /**
     * Menampilkan preview laporan analisis produk berdasarkan rentang tanggal.
     *
     * @param string $tanggal_awal
     * @param string $tanggal_akhir
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
     */
    public function previewAnalisisProduk($tanggal_awal, $tanggal_akhir)
    {
        $data = AnalisisProduk::whereBetween('tanggal', [$tanggal_awal, $tanggal_akhir])->get();
        return view('dashboard.laporan.preview.analisis_produk', compact('data', 'tanggal_awal', 'tanggal_akhir'));
    }

    /**
     * Mengunduh laporan analisis produk dalam format PDF berdasarkan rentang tanggal.
     *
     * @param string $tanggal_awal
     * @param string $tanggal_akhir
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function downloadAnalisisProduk($tanggal_awal, $tanggal_akhir)
    {
        $data = AnalisisProduk::whereBetween('tanggal', [$tanggal_awal, $tanggal_akhir])->get();
        $pdf = PDF::loadView('dashboard.laporan.pdf.analisis_produk', compact('data', 'tanggal_awal', 'tanggal_akhir'));
        return $pdf->download('laporan-analisis-produk-' . $tanggal_awal . '-' . $tanggal_akhir . '.pdf');
    }

    /**
     * Menampilkan halaman untuk mencetak laporan analisis produk berdasarkan rentang tanggal.
     *
     * @param string $tanggal_awal
     * @param string $tanggal_akhir
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
     */
    public function printAnalisisProduk($tanggal_awal, $tanggal_akhir)
    {
        $data = AnalisisProduk::whereBetween('tanggal', [$tanggal_awal, $tanggal_akhir])->get();
        return view('dashboard.laporan.print.analisis_produk', compact('data', 'tanggal_awal', 'tanggal_akhir'));
    }

    public function previewAnalisisPabrik($tanggal_awal, $tanggal_akhir)
    {
        $data = DB::table('analisis_pabrik')
            ->whereBetween('tanggal_awal', [$tanggal_awal, $tanggal_akhir])
            ->orWhereBetween('tanggal_akhir', [$tanggal_awal, $tanggal_akhir])
            ->get();
        return view('dashboard.laporan.preview.analisis_pabrik', compact('data', 'tanggal_awal', 'tanggal_akhir'));
    }

    public function downloadAnalisisPabrik($tanggal_awal, $tanggal_akhir)
    {
        $data = DB::table('analisis_pabrik')
            ->whereBetween('tanggal_awal', [$tanggal_awal, $tanggal_akhir])
            ->orWhereBetween('tanggal_akhir', [$tanggal_awal, $tanggal_akhir])
            ->get();
        $pdf = PDF::loadView('dashboard.laporan.pdf.analisis_pabrik', compact('data', 'tanggal_awal', 'tanggal_akhir'));
        return $pdf->download('laporan-analisis-pabrik-' . $tanggal_awal . '-' . $tanggal_akhir . '.pdf');
    }

    public function printAnalisisPabrik($tanggal_awal, $tanggal_akhir)
    {
        $data = DB::table('analisis_pabrik')
            ->whereBetween('tanggal_awal', [$tanggal_awal, $tanggal_akhir])
            ->orWhereBetween('tanggal_akhir', [$tanggal_awal, $tanggal_akhir])
            ->get();
        return view('dashboard.laporan.print.analisis_pabrik', compact('data', 'tanggal_awal', 'tanggal_akhir'));
    }
}