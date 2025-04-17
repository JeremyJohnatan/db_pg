<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AnalisisPabrikController extends Controller
{
    public function index(Request $request)
    {
        // Ambil parameter tanggal dari request
        $tanggalMulai = $request->input('tanggal_mulai');
        $tanggalAkhir = $request->input('tanggal_akhir');
        
        // Set default jika tidak ada tanggal yang dipilih
        if (!$tanggalMulai || !$tanggalAkhir) {
            $tanggalAkhir = Carbon::today()->format('Y-m-d');
            $tanggalMulai = Carbon::today()->subDays(30)->format('Y-m-d');
        }
        
        // Query data produksi per pabrik dengan filter tanggal
        $produksiData = DB::table('tblPengambilan')
            ->select('KdGudang', DB::raw('SUM(Jml) as total_produksi'))
            ->whereBetween('Tgl', [$tanggalMulai, $tanggalAkhir]) // Filter berdasarkan tanggal
            ->groupBy('KdGudang')
            ->get();
        
        // Array kosong untuk data penjualan (tidak akan ditampilkan)
        $penjualanData = collect();
        
        // Ambil daftar nama pabrik
        $pabrikList = DB::table('tblGudang')
            ->select('KdGudang', 'Gudang as NamaGudang')
            ->get()
            ->keyBy('KdGudang');
            
        // Siapkan data untuk chart
        $labels = [];
        $chartProduksiData = [];
        $chartPenjualanData = []; // Tetap dibuat tapi kosong
        
        // Buat daftar semua pabrik yang ada di data produksi
        $semua_pabrik = collect();
        
        $produksiData->each(function ($item) use ($semua_pabrik) {
            $semua_pabrik->put($item->KdGudang, true);
        });
        
        // Buat array untuk data progress bar dan chart
        $progressData = [];
        $totalProduksi = $produksiData->sum('total_produksi');
        
        foreach ($semua_pabrik->keys() as $kode_pabrik) {
            $nama_pabrik = $pabrikList->has($kode_pabrik) ? $pabrikList[$kode_pabrik]->NamaGudang : "Pabrik {$kode_pabrik}";
            
            // Cari data produksi untuk pabrik ini
            $produksi = $produksiData->firstWhere('KdGudang', $kode_pabrik);
            $jumlah_produksi = $produksi ? $produksi->total_produksi : 0;
            
            // Hitung persentase
            $persentase = $totalProduksi > 0 ? ($jumlah_produksi / $totalProduksi * 100) : 0;
            
            // Tambahkan ke array progress data
            $progressData[] = [
                'pabrik' => $nama_pabrik,
                'kode_pabrik' => $kode_pabrik,
                'jumlah' => $jumlah_produksi,
                'persentase' => $persentase
            ];
            
            // Tambahkan data untuk chart
            $labels[] = $nama_pabrik;
            $chartProduksiData[] = $jumlah_produksi;
            $chartPenjualanData[] = 0; // Semua nilai penjualan diset ke 0
        }
        
        // Urutkan data progres berdasarkan jumlah produksi (dari besar ke kecil)
        usort($progressData, function($a, $b) {
            return $b['jumlah'] - $a['jumlah'];
        });
        
        // Jika request AJAX, kembalikan data JSON
        if ($request->ajax()) {
            return response()->json([
                'labels' => $labels,
                'produksiData' => $chartProduksiData,
                'penjualanData' => $chartPenjualanData,
                'progressData' => $progressData
            ]);
        }
        
        // Jika bukan AJAX request, kembalikan view
        return view('dashboard.analisis-pabrik', [
            'labels' => $labels,
            'produksiData' => $chartProduksiData,
            'penjualanData' => $chartPenjualanData,
            'progressData' => $progressData,
            'tanggalMulai' => $tanggalMulai,
            'tanggalAkhir' => $tanggalAkhir
        ]);
    }
}