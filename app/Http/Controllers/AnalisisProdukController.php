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
        
        // Default to 30 days if no dates are provided
        if (!$tanggal_mulai || !$tanggal_akhir) {
            $tanggal_akhir = Carbon::now()->format('Y-m-d');
            $tanggal_mulai = Carbon::now()->subDays(30)->format('Y-m-d');
        }
        
        // Convert to Carbon objects
        $startDate = Carbon::parse($tanggal_mulai)->startOfDay();
        $endDate = Carbon::parse($tanggal_akhir)->endOfDay();
        
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
        
        // Default to 30 days if no dates are provided
        if (!$tanggal_mulai || !$tanggal_akhir) {
            $periode = $request->input('periode', 30);
            $startDate = Carbon::now()->subDays($periode);
            $endDate = Carbon::now();
        } else {
            $startDate = Carbon::parse($tanggal_mulai)->startOfDay();
            $endDate = Carbon::parse($tanggal_akhir)->endOfDay();
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
        
        // Use date range if provided
        $query = DB::table('tblJenisProduk')
            ->select(
                'tblJenisProduk.Jenis',
                DB::raw('SUM(tblKontrak.Jml) as TotalKontrak'),
                DB::raw('SUM(tblPengambilan.Jml) as TotalPengambilan')
            )
            ->leftJoin('tblKontrak', 'tblJenisProduk.KdJenis', '=', 'tblKontrak.KdJenis')
            ->leftJoin('tblPengambilan', 'tblJenisProduk.KdJenis', '=', 'tblPengambilan.KdJenis');
        
        // Apply date filter if provided
        if ($tanggal_mulai && $tanggal_akhir) {
            $startDate = Carbon::parse($tanggal_mulai)->startOfDay();
            $endDate = Carbon::parse($tanggal_akhir)->endOfDay();
            
            $query->where(function($q) use ($startDate, $endDate) {
                $q->whereBetween('tblKontrak.Tgl', [$startDate, $endDate])
                  ->orWhereBetween('tblPengambilan.Tgl', [$startDate, $endDate]);
            });
        }
            
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
        
        // Default is 6 months
        $months = [];
        $datasets = [];
        
        // Determine range based on provided dates or default to last 6 months
        if ($tanggal_mulai && $tanggal_akhir) {
            $startDate = Carbon::parse($tanggal_mulai);
            $endDate = Carbon::parse($tanggal_akhir);
            
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
        } else {
            // Default to last 6 months
            for ($i = 5; $i >= 0; $i--) {
                $months[] = Carbon::now()->subMonths($i)->format('M Y');
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
            
            if ($tanggal_mulai && $tanggal_akhir) {
                $startDate = Carbon::parse($tanggal_mulai);
                $endDate = Carbon::parse($tanggal_akhir);
                
                // If date range is more than 6 months, limit to 6 months
                $diffInMonths = $startDate->diffInMonths($endDate);
                $monthsToShow = min($diffInMonths + 1, 6);
                
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
            } else {
                // Default behavior
                for ($i = 5; $i >= 0; $i--) {
                    $startDate = Carbon::now()->subMonths($i)->startOfMonth();
                    $endDate = Carbon::now()->subMonths($i)->endOfMonth();
                    
                    $total = DB::table('tblJenisProduk')
                        ->select(DB::raw('SUM(tblKontrak.Jml) as TotalPenjualan'))
                        ->leftJoin('tblKontrak', 'tblJenisProduk.KdJenis', '=', 'tblKontrak.KdJenis')
                        ->where('tblJenisProduk.Kategori', $category)
                        ->whereBetween('tblKontrak.Tgl', [$startDate, $endDate])
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
        
        // Base query
        $query = DB::table('tblJenisProduk')
            ->select('tblJenisProduk.Kategori', DB::raw('SUM(tblKontrak.Jml) as TotalPenjualan'))
            ->leftJoin('tblKontrak', 'tblJenisProduk.KdJenis', '=', 'tblKontrak.KdJenis')
            ->where(function($q) use ($keyword) {
                $q->where('tblJenisProduk.Kategori', 'like', "%{$keyword}%")
                  ->orWhere('tblJenisProduk.Jenis', 'like', "%{$keyword}%");
            });
        
        // Apply date filter if provided
        if ($tanggal_mulai && $tanggal_akhir) {
            $startDate = Carbon::parse($tanggal_mulai)->startOfDay();
            $endDate = Carbon::parse($tanggal_akhir)->endOfDay();
            $query->whereBetween('tblKontrak.Tgl', [$startDate, $endDate]);
        }
        
        $results = $query->groupBy('tblJenisProduk.Kategori')->get();
            
        return response()->json($results);
    }
}