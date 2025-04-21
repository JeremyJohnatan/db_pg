<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AnalisisProdukController extends Controller
{
    public function index(Request $request)
    {
        // Get date range for filtering
        $tanggal_mulai = $request->input('tanggal_mulai');
        $tanggal_akhir = $request->input('tanggal_akhir');
        
        // Default ke 1 Januari 2024 sampai tanggal hari ini jika tidak ada tanggal yang disediakan
        if (!$tanggal_mulai || !$tanggal_akhir) {
            $tanggal_akhir = Carbon::now()->format('Y-m-d');
            $tanggal_mulai = Carbon::parse('2024-01-01')->format('Y-m-d');
        }

        // Validate date input
        try {
            $startDate = Carbon::parse($tanggal_mulai)->startOfDay();
            $endDate = Carbon::parse($tanggal_akhir)->endOfDay();
        } catch (\Exception $e) {
            return response()->json(['error' => 'Tanggal yang diberikan tidak valid.'], 400);
        }

        // Get sales data by category from tblKontrak
        $penjualanPerKategori = DB::table('tblJenisProduk')
            ->select('tblJenisProduk.Kategori', DB::raw('SUM(tblKontrak.Jml) as TotalPenjualan'))
            ->leftJoin('tblKontrak', 'tblJenisProduk.KdJenis', '=', 'tblKontrak.KdJenis')
            ->whereBetween('tblKontrak.Tgl', [$startDate, $endDate])
            ->groupBy('tblJenisProduk.Kategori')
            ->orderBy('TotalPenjualan', 'desc')
            ->get();
        
        // Prepare data for chart
        $labels = $penjualanPerKategori->pluck('Kategori')->toJson();
        $data = $penjualanPerKategori->pluck('TotalPenjualan')->toJson();
        
        return view('dashboard.analisis-produk', compact('penjualanPerKategori', 'labels', 'data', 'tanggal_mulai', 'tanggal_akhir'));
    }
    
    // API endpoint for product details
    public function getDetailProduk(Request $request)
    {
        // Get date range for filtering
        $tanggal_mulai = $request->input('tanggal_mulai');
        $tanggal_akhir = $request->input('tanggal_akhir');
        
        // Default ke 1 Januari 2024 sampai tanggal hari ini jika tidak ada tanggal yang disediakan
        if (!$tanggal_mulai || !$tanggal_akhir) {
            $endDate = Carbon::now();
            $startDate = Carbon::parse('2024-01-01');
        } else {
            try {
                $startDate = Carbon::parse($tanggal_mulai)->startOfDay();
                $endDate = Carbon::parse($tanggal_akhir)->endOfDay();
            } catch (\Exception $e) {
                return response()->json(['error' => 'Tanggal yang diberikan tidak valid.'], 400);
            }
        }

        $detailProduk = DB::table('tblJenisProduk')
            ->select('tblJenisProduk.Jenis', DB::raw('SUM(tblKontrak.Jml) as TotalPenjualan'))
            ->leftJoin('tblKontrak', 'tblJenisProduk.KdJenis', '=', 'tblKontrak.KdJenis')
            ->whereBetween('tblKontrak.Tgl', [$startDate, $endDate])
            ->groupBy('tblJenisProduk.Jenis')
            ->orderBy('TotalPenjualan', 'desc')
            ->get();
            
        $labels = $detailProduk->pluck('Jenis')->toArray();
        $data = $detailProduk->pluck('TotalPenjualan')->toArray();
        
        return response()->json([
            'labels' => $labels,
            'data' => $data
        ]);
    }
    
    // API endpoint for production analysis
    public function getProductionAnalysis(Request $request)
    {
        // Get date range for filtering
        $tanggal_mulai = $request->input('tanggal_mulai');
        $tanggal_akhir = $request->input('tanggal_akhir');
        
        // Default ke 1 Januari 2024 sampai tanggal hari ini jika tidak ada tanggal yang disediakan
        if (!$tanggal_mulai || !$tanggal_akhir) {
            $endDate = Carbon::now();
            $startDate = Carbon::parse('2024-01-01');
        } else {
            try {
                $startDate = Carbon::parse($tanggal_mulai)->startOfDay();
                $endDate = Carbon::parse($tanggal_akhir)->endOfDay();
            } catch (\Exception $e) {
                return response()->json(['error' => 'Tanggal yang diberikan tidak valid.'], 400);
            }
        }
        
        // Use date range if provided
        $query = DB::table('tblJenisProduk')
            ->select(
                'tblJenisProduk.Jenis',
                DB::raw('SUM(tblKontrak.Jml) as TotalKontrak'),
                DB::raw('SUM(tblPengambilan.Jml) as TotalPengambilan')
            )
            ->leftJoin('tblKontrak', 'tblJenisProduk.KdJenis', '=', 'tblKontrak.KdJenis')
            ->leftJoin('tblPengambilan', 'tblJenisProduk.KdJenis', '=', 'tblPengambilan.KdJenis');
            
        $query->where(function($q) use ($startDate, $endDate) {
            $q->whereBetween('tblKontrak.Tgl', [$startDate, $endDate])
              ->orWhereBetween('tblPengambilan.Tgl', [$startDate, $endDate]);
        });
            
        $perbandingan = $query->groupBy('tblJenisProduk.Jenis')->get();
            
        return response()->json([
            'perbandingan' => $perbandingan
        ]);
    }
    
    // API endpoint for product trends
    public function getProductTrends(Request $request)
    {
        // Get date range for filtering
        $tanggal_mulai = $request->input('tanggal_mulai');
        $tanggal_akhir = $request->input('tanggal_akhir');
        
        // Default ke 1 Januari 2024 sampai tanggal hari ini jika tidak ada tanggal yang disediakan
        if (!$tanggal_mulai || !$tanggal_akhir) {
            $endDate = Carbon::now();
            $startDate = Carbon::parse('2024-01-01');
        } else {
            try {
                $startDate = Carbon::parse($tanggal_mulai);
                $endDate = Carbon::parse($tanggal_akhir);
            } catch (\Exception $e) {
                return response()->json(['error' => 'Tanggal yang diberikan tidak valid.'], 400);
            }
        }

        // Determine range based on provided dates or default to last 6 months
        $months = [];
        $datasets = [];
        
        // If date range is more than 6 months, limit to 6 months
        $diffInMonths = $startDate->diffInMonths($endDate);
        $monthsToShow = min($diffInMonths + 1, 6);
        
        // Generate month labels based on date range
        for ($i = 0; $i < $monthsToShow; $i++) {
            $currentDate = (clone $startDate)->addMonths($i);
            if ($currentDate->lte($endDate)) {
                $months[] = $currentDate->format('M Y');
            }
        }

        // Get categories
        $categories = DB::table('tblJenisProduk')
            ->select('Kategori')
            ->distinct()
            ->get()
            ->pluck('Kategori')
            ->toArray();
            
        // Get data for each category by month
        foreach ($categories as $category) {
            $data = [];
            
            for ($i = 0; $i < $monthsToShow; $i++) {
                $currentDate = (clone $startDate)->addMonths($i);
                if ($currentDate->lte($endDate)) {
                    $monthStartDate = (clone $currentDate)->startOfMonth();
                    $monthEndDate = (clone $currentDate)->endOfMonth();
                    
                    if ($monthEndDate->gt($endDate)) {
                        $monthEndDate = $endDate;
                    }
                    
                    $total = DB::table('tblJenisProduk')
                        ->select(DB::raw('SUM(tblKontrak.Jml) as TotalPenjualan'))
                        ->leftJoin('tblKontrak', 'tblJenisProduk.KdJenis', '=', 'tblKontrak.KdJenis')
                        ->where('tblJenisProduk.Kategori', $category)
                        ->whereBetween('tblKontrak.Tgl', [$monthStartDate, $monthEndDate])
                        ->value('TotalPenjualan') ?? 0;
                        
                    $data[] = $total;
                }
            }
            
            $datasets[] = [
                'label' => $category,
                'data' => $data
            ];
        }
        
        return response()->json([
            'labels' => $months,
            'datasets' => $datasets
        ]);
    }
    
    // API endpoint for search
    public function searchProduct(Request $request)
    {
        $keyword = $request->input('keyword');
        $tanggal_mulai = $request->input('tanggal_mulai');
        $tanggal_akhir = $request->input('tanggal_akhir');
        
        // Default ke 1 Januari 2024 sampai tanggal hari ini jika tidak ada tanggal yang disediakan
        if (!$tanggal_mulai || !$tanggal_akhir) {
            $endDate = Carbon::now();
            $startDate = Carbon::parse('2024-01-01');
        } else {
            try {
                $startDate = Carbon::parse($tanggal_mulai)->startOfDay();
                $endDate = Carbon::parse($tanggal_akhir)->endOfDay();
            } catch (\Exception $e) {
                return response()->json(['error' => 'Tanggal yang diberikan tidak valid.'], 400);
            }
        }
        
        // Base query
        $query = DB::table('tblJenisProduk')
            ->select('tblJenisProduk.Kategori', DB::raw('SUM(tblKontrak.Jml) as TotalPenjualan'))
            ->leftJoin('tblKontrak', 'tblJenisProduk.KdJenis', '=', 'tblKontrak.KdJenis')
            ->where(function($q) use ($keyword) {
                $q->where('tblJenisProduk.Kategori', 'like', "%{$keyword}%")
                  ->orWhere('tblJenisProduk.Jenis', 'like', "%{$keyword}%");
            })
            ->whereBetween('tblKontrak.Tgl', [$startDate, $endDate]);
        
        $results = $query->groupBy('tblJenisProduk.Kategori')->get();
            
        return response()->json($results);
    }
}