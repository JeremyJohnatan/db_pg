<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Log;

class StaffController extends Controller
{
    public function dashboard(Request $request)
    {
        // Fetch staff user information
        $user = DB::table('pkl.users')
            ->where('id', session('user_id'))
            ->first();

        // You can add more data retrieval as needed
        $dashboardData = [
            'user' => $user,
            // Add more dashboard-specific data here
        ];

        try {
            // Ambil parameter tanggal dari request
            $tanggalMulai = $request->input('tanggal_mulai');
            $tanggalAkhir = $request->input('tanggal_akhir');
            
            // Default ke 1 Januari 2024 sampai tanggal hari ini jika tidak ada tanggal yang disediakan
            if (!$tanggalMulai || !$tanggalAkhir) {
                $tanggalAkhir = Carbon::today()->format('Y-m-d');
                $tanggalMulai = Carbon::parse('2024-01-01')->format('Y-m-d');
            }
            
            // Query untuk total kontrak
            $totalKontrak = DB::table('tblKontrak')
                ->whereBetween('Tgl', [$tanggalMulai, $tanggalAkhir])
                ->sum('Jml');
                
            // Query untuk rata-rata kontrak
            $rataRataKontrak = DB::table('tblKontrak')
                ->whereBetween('Tgl', [$tanggalMulai, $tanggalAkhir])
                ->avg('Jml');
                
            // Query untuk produk terlaris
            $produkTerlaris = DB::table('tblJenisProduk')
                ->select('tblJenisProduk.Jenis', DB::raw('SUM(tblKontrak.Jml) as TotalPenjualan'))
                ->leftJoin('tblKontrak', 'tblJenisProduk.KdJenis', '=', 'tblKontrak.KdJenis')
                ->whereBetween('tblKontrak.Tgl', [$tanggalMulai, $tanggalAkhir])
                ->groupBy('tblJenisProduk.Jenis')
                ->orderBy('TotalPenjualan', 'desc')
                ->first();
                
            // Query untuk total pengambilan
            $totalPengambilan = DB::table('tblPengambilan')
                ->whereBetween('Tgl', [$tanggalMulai, $tanggalAkhir])
                ->sum('Jml');
                
            // Query untuk rata-rata produksi
            $rataRataProduksi = DB::table('tblPengambilan')
                ->whereBetween('Tgl', [$tanggalMulai, $tanggalAkhir])
                ->avg('Jml');
                
            // Query untuk data produksi vs kontrak per bulan
            $dataBulan = [];
            $dataKontrak = [];
            $dataProduksi = [];
            
            // Menghitung rentang bulan antara tanggal mulai dan akhir
            $startMonth = Carbon::parse($tanggalMulai)->startOfMonth();
            $endMonth = Carbon::parse($tanggalAkhir)->endOfMonth();
            
            for ($date = $startMonth; $date->lte($endMonth); $date->addMonth()) {
                $bulanTahun = $date->format('M Y');
                $bulanAwal = $date->copy()->startOfMonth()->format('Y-m-d');
                $bulanAkhir = $date->copy()->endOfMonth()->format('Y-m-d');
                
                // Data kontrak per bulan
                $kontrakBulan = DB::table('tblKontrak')
                    ->whereBetween('Tgl', [$bulanAwal, $bulanAkhir])
                    ->sum('Jml');
                    
                // Data produksi per bulan
                $produksiBulan = DB::table('tblPengambilan')
                    ->whereBetween('Tgl', [$bulanAwal, $bulanAkhir])
                    ->sum('Jml');
                    
                $dataBulan[] = $bulanTahun;
                $dataKontrak[] = $kontrakBulan;
                $dataProduksi[] = $produksiBulan;
            }
            
            // Query untuk data total penjualan per kategori
            $kategoriPenjualan = DB::table('tblJenisProduk')
                ->select('tblJenisProduk.Kategori', DB::raw('SUM(tblKontrak.Jml) as TotalPenjualan'))
                ->leftJoin('tblKontrak', 'tblJenisProduk.KdJenis', '=', 'tblKontrak.KdJenis')
                ->whereBetween('tblKontrak.Tgl', [$tanggalMulai, $tanggalAkhir])
                ->groupBy('tblJenisProduk.Kategori')
                ->get();
                
            $labelKategori = $kategoriPenjualan->pluck('Kategori')->toArray();
            $dataPenjualan = $kategoriPenjualan->pluck('TotalPenjualan')->toArray();
            
            return view('dashboard.index', [
                'totalKontrak' => $totalKontrak,
                'rataRataKontrak' => $rataRataKontrak,
                'produkTerlaris' => $produkTerlaris ? $produkTerlaris->Jenis : 'Tidak ada data',
                'totalPengambilan' => $totalPengambilan,
                'rataRataProduksi' => $rataRataProduksi,
                'dataBulan' => json_encode($dataBulan),
                'dataKontrak' => json_encode($dataKontrak),
                'dataProduksi' => json_encode($dataProduksi),
                'labelKategori' => json_encode($labelKategori),
                'dataPenjualan' => json_encode($dataPenjualan),
                'tanggalMulai' => $tanggalMulai,
                'tanggalAkhir' => $tanggalAkhir
            ]);
        } catch (Exception $e) {
            // Log error dan tampilkan halaman dashboard dengan data minimal
            Log::error('Dashboard error: ' . $e->getMessage());
            
            return view('dashboard.index', [
                'totalKontrak' => 0,
                'rataRataKontrak' => 0,
                'produkTerlaris' => 'Tidak ada data',
                'totalPengambilan' => 0,
                'rataRataProduksi' => 0,
                'dataBulan' => json_encode([]),
                'dataKontrak' => json_encode([]),
                'dataProduksi' => json_encode([]),
                'labelKategori' => json_encode([]),
                'dataPenjualan' => json_encode([]),
                'tanggalMulai' => Carbon::parse('2024-01-01')->format('Y-m-d'),
                'tanggalAkhir' => Carbon::today()->format('Y-m-d'),
                'error' => $e->getMessage()
            ]);
        }

        return view('dashboard.staff.dashboard', $dashboardData);
    }
}