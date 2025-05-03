<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDF;
use Carbon\Carbon;
use App\Models\AnalisisProduk;
use ZipArchive;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class LaporanController extends Controller
{
    /**
     * Menampilkan halaman utama laporan di dashboard.
     *
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
     */
    public function indexDashboard()
    {
        // Ambil semua laporan dari tabel pkl.laporan
        $reports = DB::table('pkl.laporan')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        // Convert string dates to Carbon objects for proper formatting in view
        foreach ($reports as $report) {
            $report->created_at = Carbon::parse($report->created_at);
            $report->tanggal_mulai = Carbon::parse($report->tanggal_mulai);
            $report->tanggal_akhir = Carbon::parse($report->tanggal_akhir);
        }
        
        return view('dashboard.laporan', compact('reports'));
    }

    /**
     * Menyimpan laporan baru
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Validasi request
        $validated = $request->validate([
            'jenis_laporan' => 'required|string',
            'tanggal_mulai' => 'required|date',
            'tanggal_akhir' => 'required|date|after_or_equal:tanggal_mulai',
        ]);
        
        // Buat laporan baru
        $laporan = [
            'jenis_laporan' => $validated['jenis_laporan'],
            'tanggal_mulai' => $validated['tanggal_mulai'],
            'tanggal_akhir' => $validated['tanggal_akhir'],
            'status' => 'Selesai',
            'created_at' => now(),
            'updated_at' => now()
        ];
        
        // Insert ke database
        $id = DB::table('pkl.laporan')->insertGetId($laporan);
        
        return redirect()->route('dashboard.laporan')->with('success', 'Laporan berhasil dibuat');
    }

    /**
     * Menampilkan preview laporan.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function preview($id)
    {
        $report = DB::table('pkl.laporan')->where('id', $id)->first();
        
        if (!$report) {
            return response()->json(['error' => 'Laporan tidak ditemukan'], 404);
        }
        
        // Generate content berdasarkan jenis laporan
        $content = $this->generateReportContent($report);
        
        // Tambahkan informasi laporan ke content
        $content['id'] = $report->id;
        $content['jenis_laporan'] = $report->jenis_laporan;
        $content['tanggal_mulai'] = Carbon::parse($report->tanggal_mulai)->format('d F Y');
        $content['tanggal_akhir'] = Carbon::parse($report->tanggal_akhir)->format('d F Y');
        
        return response()->json($content);
    }

    /**
     * Mengunduh laporan dalam format PDF.
     *
     * @param  int  $id
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function download($id)
    {
        $report = DB::table('pkl.laporan')->where('id', $id)->first();
        
        if (!$report) {
            abort(404, 'Laporan tidak ditemukan');
        }
        
        // Generate content berdasarkan jenis laporan
        $content = $this->generateReportContent($report);
        
        // Tambahkan informasi laporan ke content
        $content['jenis_laporan'] = $report->jenis_laporan;
        $content['tanggal_mulai'] = Carbon::parse($report->tanggal_mulai)->format('d F Y');
        $content['tanggal_akhir'] = Carbon::parse($report->tanggal_akhir)->format('d F Y');
        
        // Generate PDF
        $pdf = PDF::loadView('laporan.pdf', compact('report', 'content'));
        
        return $pdf->download('Laporan-' . $id . '.pdf');
    }

    /**
     * Menampilkan halaman untuk mencetak laporan.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
     */
    public function print($id)
    {
        $report = DB::table('pkl.laporan')->where('id', $id)->first();
        
        if (!$report) {
            abort(404, 'Laporan tidak ditemukan');
        }
        
        // Generate content berdasarkan jenis laporan
        $content = $this->generateReportContent($report);
        
        // Tambahkan informasi laporan ke content
        $content['jenis_laporan'] = $report->jenis_laporan;
        $content['tanggal_mulai'] = Carbon::parse($report->tanggal_mulai)->format('d F Y');
        $content['tanggal_akhir'] = Carbon::parse($report->tanggal_akhir)->format('d F Y');
        
        return view('laporan.print', compact('report', 'content'));
    }

    /**
     * Mengunduh semua laporan dalam format ZIP.
     *
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function downloadAll()
    {
        $reports = DB::table('pkl.laporan')->get();
        
        // Create temporary directory for PDFs
        $tempDir = storage_path('app/temp/reports-' . time());
        if (!file_exists($tempDir)) {
            mkdir($tempDir, 0755, true);
        }
        
        // Generate PDFs for all reports
        foreach ($reports as $report) {
            $content = $this->generateReportContent($report);
            
            // Tambahkan informasi laporan ke content
            $content['jenis_laporan'] = $report->jenis_laporan;
            $content['tanggal_mulai'] = Carbon::parse($report->tanggal_mulai)->format('d F Y');
            $content['tanggal_akhir'] = Carbon::parse($report->tanggal_akhir)->format('d F Y');
            
            $pdf = PDF::loadView('laporan.pdf', compact('report', 'content'));
            $pdf->save($tempDir . '/Laporan-' . $report->id . '.pdf');
        }
        
        // Create ZIP archive
        $zipFileName = 'Semua-Laporan-' . date('Y-m-d') . '.zip';
        $zipFilePath = storage_path('app/temp/' . $zipFileName);
        
        $zip = new ZipArchive();
        if ($zip->open($zipFilePath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
            $files = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($tempDir),
                \RecursiveIteratorIterator::LEAVES_ONLY
            );
            
            foreach ($files as $file) {
                if (!$file->isDir()) {
                    $filePath = $file->getRealPath();
                    $relativePath = basename($filePath);
                    $zip->addFile($filePath, $relativePath);
                }
            }
            
            $zip->close();
        }
        
        // Clean up temporary files
        $this->cleanTempFiles($tempDir);
        
        return response()->download($zipFilePath)->deleteFileAfterSend(true);
    }

    /**
     * Pembersihan file temporary.
     *
     * @param  string  $dir
     * @return void
     */
    private function cleanTempFiles($dir)
    {
        if (is_dir($dir)) {
            $objects = scandir($dir);
            foreach ($objects as $object) {
                if ($object != "." && $object != "..") {
                    if (is_dir($dir. DIRECTORY_SEPARATOR .$object)) {
                        $this->cleanTempFiles($dir. DIRECTORY_SEPARATOR .$object);
                    } else {
                        unlink($dir. DIRECTORY_SEPARATOR .$object);
                    }
                }
            }
            rmdir($dir);
        }
    }

    /**
     * Generate report content berdasarkan jenis laporan.
     *
     * @param  object  $report
     * @return array
     */
    private function generateReportContent($report)
    {
        $startDate = Carbon::parse($report->tanggal_mulai);
        $endDate = Carbon::parse($report->tanggal_akhir);
        
        switch ($report->jenis_laporan) {
            case 'Laporan Produksi Per Kategori':
                return $this->generateProduksiPerKategori($startDate, $endDate);
                
            case 'Laporan Analisis Produk Mingguan':
                return $this->generateAnalisisProdukMingguan($startDate, $endDate);
                
            case 'Laporan Stok Barang Bulanan':
                return $this->generateStokBarangBulanan($startDate, $endDate);
                
            case 'Laporan Penjualan Bulanan':
                return $this->generatePenjualanBulanan($startDate, $endDate);
                
            case 'Laporan Kinerja Produksi':
                return $this->generateKinerjaProduksi($startDate, $endDate);
                
            default:
                return ['title' => 'Laporan Tidak Tersedia', 'data' => []];
        }
    }

    /**
     * Generate Produksi Per Kategori report content.
     *
     * @param  \Carbon\Carbon  $startDate
     * @param  \Carbon\Carbon  $endDate
     * @return array
     */
    private function generateProduksiPerKategori($startDate, $endDate)
    {
        try {
            $data = DB::table('dbo.tblProduksi')
                ->join('dbo.tblJenisProduk', 'dbo.tblProduksi.KdJenis', '=', 'dbo.tblJenisProduk.KdJenis')
                ->select('dbo.tblJenisProduk.Jenis as kategori', DB::raw('SUM(dbo.tblProduksi.Produksi) as total_produksi'))
                ->whereBetween('dbo.tblProduksi.tanggal', [$startDate, $endDate])
                ->groupBy('dbo.tblJenisProduk.Jenis')
                ->get();
            
            $chartData = [];
            foreach ($data as $item) {
                $chartData[] = [
                    'kategori' => $item->kategori,
                    'total' => $item->total_produksi
                ];
            }
            
            return [
                'title' => 'Laporan Produksi Per Kategori',
                'data' => $data,
                'chart_data' => $chartData
            ];
        } catch (\Exception $e) {
            // Handle error dan kembalikan dummy data jika terjadi error
            return [
                'title' => 'Laporan Produksi Per Kategori',
                'data' => [],
                'chart_data' => [],
                'error' => $e->getMessage()
            ];
        }
    }

    //Analisis Produk Mingguan
    private function generateAnalisisProdukMingguan($startDate, $endDate)
{
    try {
        $data = DB::table('dbo.tblProduksi')
            ->join('dbo.tblJenisProduk', 'dbo.tblProduksi.KdJenis', '=', 'dbo.tblJenisProduk.KdJenis')
            ->select(
                DB::raw("DATENAME(week, dbo.tblProduksi.tanggal) as minggu_ke"),
                DB::raw("MIN(dbo.tblProduksi.tanggal) as awal_minggu"),
                DB::raw("MAX(dbo.tblProduksi.tanggal) as akhir_minggu"),
                'dbo.tblJenisProduk.Jenis as kategori',
                DB::raw('SUM(dbo.tblProduksi.Produksi) as total_produksi')
            )
            ->whereBetween('dbo.tblProduksi.tanggal', [$startDate, $endDate])
            ->groupBy(DB::raw("DATENAME(week, dbo.tblProduksi.tanggal)"), 'dbo.tblJenisProduk.Jenis')
            ->orderBy(DB::raw("MIN(dbo.tblProduksi.tanggal)"))
            ->get();

        $formattedData = [];

        foreach ($data as $item) {
            $minggu = 'Minggu ke-' . $item->minggu_ke . ' (' . date('d M', strtotime($item->awal_minggu)) . ' - ' . date('d M', strtotime($item->akhir_minggu)) . ')';
            $kategori = $item->kategori;
            $total = $item->total_produksi;

            if (!isset($formattedData[$minggu])) {
                $formattedData[$minggu] = [];
            }

            $formattedData[$minggu][$kategori] = $total;
        }

        return [
            'title' => 'Laporan Analisis Produk Mingguan',
            'data' => $formattedData,
            'chart_data' => $formattedData,
        ];

    } catch (\Exception $e) {
        return [
            'error' => true,
            'message' => 'Gagal mengambil data: ' . $e->getMessage()
        ];
    }

}

    //Analisis Produk Mingguan
    private function generateStokBarangBulanan($startDate, $endDate)
{
    try {
        // Ambil data produksi per bulan dan kategori
        $data = DB::table('dbo.tblProduksi')
            ->join('dbo.tblJenisProduk', 'tblProduksi.KdJenis', '=', 'tblJenisProduk.KdJenis')
            ->select(
                DB::raw("FORMAT(tblProduksi.tanggal, 'yyyy-MM') as bulan"),
                'tblJenisProduk.Jenis as kategori',
                DB::raw('SUM(tblProduksi.Produksi) as total_produksi')
            )
            ->whereBetween('tblProduksi.tanggal', [$startDate, $endDate])
            ->groupBy(
                DB::raw("FORMAT(tblProduksi.tanggal, 'yyyy-MM')"),
                'tblJenisProduk.Jenis'
            )
            ->orderBy('bulan', 'asc')
            ->get();

        // Siapkan data chart
        $chartData = [];
        foreach ($data as $item) {
            $chartData[] = [
                'bulan' => $item->bulan,
                'kategori' => $item->kategori,
                'total' => $item->total_produksi // <- perbaiki dari total_stok ke total_produksi
            ];
        }

        // Return hasil
        return [
            'title' => 'Laporan Stok Barang Bulanan',
            'jenis_laporan' => 'Laporan Stok Barang Bulanan',
            'tanggal_mulai' => date('d F Y', strtotime($startDate)),
            'tanggal_akhir' => date('d F Y', strtotime($endDate)),
            'data' => $data,
            'chart_data' => $chartData
        ];

    } catch (\Exception $e) {
        // Jika error, kembalikan dengan pesan error
        return [
            'title' => 'Laporan Stok Barang Bulanan',
            'data' => [],
            'chart_data' => [],
            'error' => $e->getMessage()
        ];
    
}

}

    
    /**
 * Menghapus laporan.
 *
 * /**
 * @param  int  $id
 * @return \Illuminate\Http\RedirectResponse
 */
public function destroy($id)
{
    try {
        // Hapus laporan dari database
        $deleted = DB::table('pkl.laporan')->where('id', $id)->delete();
        
        if ($deleted) {
            return redirect()->route('dashboard.laporan')->with('success', 'Laporan berhasil dihapus');
        } else {
            return redirect()->route('dashboard.laporan')->with('error', 'Laporan tidak ditemukan');
        }
    } catch (\Exception $e) {
        // Log error jika terjadi masalah
        Log::error('Gagal menghapus laporan: ' . $e->getMessage());
        return redirect()->route('dashboard.laporan')->with('error', 'Gagal menghapus laporan');
    }
}
}