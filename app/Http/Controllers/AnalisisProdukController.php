<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AnalisisProdukController extends Controller
{
    public function index(Request $request)
    {
        // Get period for filtering
        $periode = $request->input('periode', 30);
        $startDate = Carbon::now()->subDays($periode);
        
        // Get sales data by category
        $penjualanPerKategori = DB::table('tblJenisProduk')
            ->select('tblJenisProduk.Kategori', DB::raw('SUM(tblPengambilan.Jml) as TotalPenjualan'))
            ->leftJoin('tblPengambilan', 'tblJenisProduk.KdJenis', '=', 'tblPengambilan.KdJenis')
            ->where('tblPengambilan.Tgl', '>=', $startDate)
            ->groupBy('tblJenisProduk.Kategori')
            ->orderBy('TotalPenjualan', 'desc')
            ->get();
            
        // Prepare data for chart
        $labels = $penjualanPerKategori->pluck('Kategori')->toJson();
        $data = $penjualanPerKategori->pluck('TotalPenjualan')->toJson();
        
        return view('dashboard.analisis-produk', compact('penjualanPerKategori', 'labels', 'data', 'periode'));
    }
    
    // API endpoint for product details
    public function getDetailProduk(Request $request)
    {
        $periode = $request->input('periode', 30);
        $startDate = Carbon::now()->subDays($periode);
        
        $detailProduk = DB::table('tblJenisProduk')
            ->select('tblJenisProduk.Jenis', DB::raw('SUM(tblPengambilan.Jml) as TotalPenjualan'))
            ->leftJoin('tblPengambilan', 'tblJenisProduk.KdJenis', '=', 'tblPengambilan.KdJenis')
            ->where('tblPengambilan.Tgl', '>=', $startDate)
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
    public function getProductionAnalysis()
    {
        $perbandingan = DB::table('tblJenisProduk')
            ->select(
                'tblJenisProduk.Jenis',
                DB::raw('SUM(tblKontrak.Jml) as TotalKontrak'),
                DB::raw('SUM(tblPengambilan.Jml) as TotalPengambilan')
            )
            ->leftJoin('tblKontrak', 'tblJenisProduk.KdJenis', '=', 'tblKontrak.KdJenis')
            ->leftJoin('tblPengambilan', 'tblJenisProduk.KdJenis', '=', 'tblPengambilan.KdJenis')
            ->groupBy('tblJenisProduk.Jenis')
            ->get();
            
        return response()->json([
            'perbandingan' => $perbandingan
        ]);
    }
    
    // API endpoint for product trends
    public function getProductTrends()
    {
        // Get last 6 months
        $months = [];
        $datasets = [];
        
        // Generate last 6 months
        for ($i = 5; $i >= 0; $i--) {
            $months[] = Carbon::now()->subMonths($i)->format('M Y');
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
            
            for ($i = 5; $i >= 0; $i--) {
                $startDate = Carbon::now()->subMonths($i)->startOfMonth();
                $endDate = Carbon::now()->subMonths($i)->endOfMonth();
                
                $total = DB::table('tblJenisProduk')
                    ->select(DB::raw('SUM(tblPengambilan.Jml) as TotalPenjualan'))
                    ->leftJoin('tblPengambilan', 'tblJenisProduk.KdJenis', '=', 'tblPengambilan.KdJenis')
                    ->where('tblJenisProduk.Kategori', $category)
                    ->whereBetween('tblPengambilan.Tgl', [$startDate, $endDate])
                    ->value('TotalPenjualan') ?? 0;
                    
                $data[] = $total;
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
        
        $results = DB::table('tblJenisProduk')
            ->select('tblJenisProduk.Kategori', DB::raw('SUM(tblPengambilan.Jml) as TotalPenjualan'))
            ->leftJoin('tblPengambilan', 'tblJenisProduk.KdJenis', '=', 'tblPengambilan.KdJenis')
            ->where('tblJenisProduk.Kategori', 'like', "%{$keyword}%")
            ->orWhere('tblJenisProduk.Jenis', 'like', "%{$keyword}%")
            ->groupBy('tblJenisProduk.Kategori')
            ->get();
            
        return response()->json($results);
    }
}