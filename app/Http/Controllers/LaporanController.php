<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\AnalisisProduk;
use ZipArchive;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

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
     * Mengunduh laporan dalam format PDF menggunakan FPDF.
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
    
    // Generate PDF menggunakan FPDF
    $pdf = new \FPDF('P', 'mm', 'A4');
    $pdf->AddPage();
    
    // Set judul
    $pdf->SetFont('Arial', 'B', 16);
    $pdf->Cell(0, 10, $content['jenis_laporan'], 0, 1, 'C');
    
    // Set periode
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(0, 10, 'Periode: ' . $content['tanggal_mulai'] . ' - ' . $content['tanggal_akhir'], 0, 1, 'C');
    $pdf->Ln(5);
    
    // Render content berdasarkan jenis laporan
    switch ($report->jenis_laporan) {
        case 'Laporan Produksi Per Kategori':
            $this->renderProduksiPerKategoriPDF($pdf, $content);
            break;
            
        case 'Laporan Analisis Produk Mingguan':
            $this->renderAnalisisProdukMingguanPDF($pdf, $content);
            break;
            
        case 'Laporan Stok Barang Bulanan':
            $this->renderStokBarangBulananPDF($pdf, $content);
            break;
            
        case 'Laporan Penjualan Bulanan':
            $this->renderPenjualanBulananPDF($pdf, $content);
            break;
            
        case 'Laporan Kinerja Produksi':
            $this->renderKinerjaProduksiPDF($pdf, $content);
            break;
            
        default:
            $pdf->Cell(0, 10, 'Tipe laporan tidak tersedia', 0, 1, 'C');
            break;
    }
    
    // Perubahan disini: Output PDF sebagai inline stream untuk preview
    $fileName = 'Laporan-' . $id . '.pdf';
    return response($pdf->Output('S', $fileName), 200, [
        'Content-Type' => 'application/pdf',
        'Content-Disposition' => 'inline; filename="' . $fileName . '"', // Ubah 'attachment' menjadi 'inline'
    ]);
}

    /**
     * Mengunduh semua laporan dalam format ZIP.
     *
    /**
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

        // Generate PDF menggunakan FPDF
        $pdf = new \FPDF('P', 'mm', 'A4');
        $pdf->AddPage();

        $pdf->SetFont('Arial', 'B', 16);
        $pdf->Cell(0, 10, $content['jenis_laporan'], 0, 1, 'C');

        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(0, 10, 'Periode: ' . $content['tanggal_mulai'] . ' - ' . $content['tanggal_akhir'], 0, 1, 'C');
        $pdf->Ln(5);

        // Render content
        switch ($report->jenis_laporan) {
            case 'Laporan Produksi Per Kategori':
                $this->renderProduksiPerKategoriPDF($pdf, $content);
                break;
            case 'Laporan Analisis Produk Mingguan':
                $this->renderAnalisisProdukMingguanPDF($pdf, $content);
                break;
            case 'Laporan Stok Barang Bulanan':
                $this->renderStokBarangBulananPDF($pdf, $content);
                break;
            case 'Laporan Penjualan Bulanan':
                $this->renderPenjualanBulananPDF($pdf, $content);
                break;
            case 'Laporan Kinerja Produksi':
                $this->renderKinerjaProduksiPDF($pdf, $content);
                break;
            default:
                $pdf->Cell(0, 10, 'Tipe laporan tidak tersedia', 0, 1, 'C');
                break;
        }

        $pdf->Output('F', $tempDir . '/Laporan-' . $report->id . '.pdf');
    }

    // Create ZIP archive
    $zipFileName = 'Semua-Laporan-' . date('Y-m-d') . '.zip';
    $zipFilePath = storage_path('app/temp/' . $zipFileName);

    try {
        $zip = new \ZipArchive();
        $result = $zip->open($zipFilePath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);

        if ($result !== TRUE) {
            throw new \Exception("Gagal membuat file ZIP: kode error " . $result);
        }

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

        // Clean up temporary files
        $this->cleanTempFiles($tempDir);

        return response()->download($zipFilePath)->deleteFileAfterSend(true);
    } catch (\Exception $e) {
        Log::error('Error creating ZIP file: ' . $e->getMessage());
        return redirect()->back()->with('error', 'Gagal membuat file ZIP: ' . $e->getMessage());
    }
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
                'message' => 'Gagal mengambil data: ' . $e->getMessage(),
                'title' => 'Laporan Analisis Produk Mingguan',
                'data' => [],
                'chart_data' => []
            ];
        }
    }

    //Stok Barang Bulanan
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
                    'total' => $item->total_produksi
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
    
    // Generate Penjualan Bulanan Report
    private function generatePenjualanBulanan($startDate, $endDate)
{
    try {
        // Get data grouped by month and product
        $productData = DB::table('tblKontrak')
            ->join('tblJenisProduk', 'tblKontrak.KdJenis', '=', 'tblJenisProduk.KdJenis')
            ->select(
                DB::raw("FORMAT(tblKontrak.Tgl, 'MMMM yyyy') as bulan"),
                'tblJenisProduk.Jenis as produk',
                DB::raw('SUM(tblKontrak.Jml) as total_pengambilan')
            )
            ->whereBetween('tblKontrak.Tgl', [$startDate, $endDate])
            ->groupBy(DB::raw("FORMAT(tblKontrak.Tgl, 'MMMM yyyy')"), 'tblJenisProduk.Jenis')
            ->orderBy(DB::raw("MIN(tblKontrak.Tgl)"))
            ->get();

        // Get data grouped by month and buyer (Pembeli)
        $buyerData = DB::table('tblKontrak')
            ->join('tblPembeli', 'tblKontrak.KdPembeli', '=', 'tblPembeli.KdPembeli')
            ->select(
                DB::raw("FORMAT(tblKontrak.Tgl, 'MMMM yyyy') as bulan"),
                'tblPembeli.Pembeli as pembeli',  // Column is Pembeli, not Nama
                DB::raw('SUM(tblKontrak.Jml) as total_pengambilan')
            )
            ->whereBetween('tblKontrak.Tgl', [$startDate, $endDate])
            ->groupBy(DB::raw("FORMAT(tblKontrak.Tgl, 'MMMM yyyy')"), 'tblPembeli.Pembeli')
            ->orderBy(DB::raw("MIN(tblKontrak.Tgl)"))
            ->get();

        // Format data into the required structure
        $formattedData = [];

        // Format product data
        foreach ($productData as $item) {
            if (!isset($formattedData[$item->bulan])) {
                $formattedData[$item->bulan] = [
                    'produk' => [],
                    'pembeli' => []
                ];
            }
            
            $formattedData[$item->bulan]['produk'][$item->produk] = $item->total_pengambilan;
        }

        // Format buyer data
        foreach ($buyerData as $item) {
            if (!isset($formattedData[$item->bulan])) {
                $formattedData[$item->bulan] = [
                    'produk' => [],
                    'pembeli' => []
                ];
            }
            
            $formattedData[$item->bulan]['pembeli'][$item->pembeli] = $item->total_pengambilan;
        }

        // If no data found, provide default empty month
        if (empty($formattedData)) {
            $currentMonth = Carbon::now()->format('F Y');
            $formattedData[$currentMonth] = [
                'produk' => [],
                'pembeli' => []
            ];
        }

        // Calculate total monthly sales
        $monthlyTotals = [];
        foreach ($formattedData as $month => $data) {
            $monthlyTotals[$month] = array_sum($data['produk']);
        }

        return [
            'title' => 'Laporan Penjualan Bulanan',
            'jenis_laporan' => 'Laporan Penjualan Bulanan',
            'tanggal_mulai' => $startDate->format('d F Y'),
            'tanggal_akhir' => $endDate->format('d F Y'),
            'data' => $formattedData,
            'chart_data' => $formattedData,
            'monthly_totals' => $monthlyTotals
        ];
    } catch (\Exception $e) {
        // Log the error for debugging
        Log::error('Error generating Penjualan Bulanan report: ' . $e->getMessage());
        
        // Return error data
        return [
            'title' => 'Laporan Penjualan Bulanan',
            'jenis_laporan' => 'Laporan Penjualan Bulanan',
            'tanggal_mulai' => $startDate->format('d F Y'),
            'tanggal_akhir' => $endDate->format('d F Y'),
            'data' => [],
            'chart_data' => [],
            'error' => $e->getMessage()
        ];
    }

    }
    
    // Generate Kinerja Produksi Report
    private function generateKinerjaProduksi($startDate, $endDate)
{
    try {
        // Hitung total hari dalam periode yang dipilih
        $totalDays = $startDate->diffInDays($endDate) + 1;
        
        // Hitung jumlah hari dengan aktivitas produksi
        $daysWithProduction = DB::table('tblProduksi')
            ->select(DB::raw('COUNT(DISTINCT Tanggal) as days_count'))
            ->whereBetween('Tanggal', [$startDate, $endDate])
            ->value('days_count');
        
        // Hitung persentase efisiensi
        $efisiensi = $totalDays > 0 ? round(($daysWithProduction / $totalDays) * 100, 2) : 0;
        
        // Hitung total produksi
        $totalProduksi = DB::table('tblProduksi')
            ->whereBetween('Tanggal', [$startDate, $endDate])
            ->sum('Produksi');
        
        // Hitung rata-rata produksi harian pada hari produksi
        $rataProduksi = $daysWithProduction > 0 ? round($totalProduksi / $daysWithProduction) : 0;
        
        // Dapatkan data produksi per pabrik
        $produksiByPabrik = DB::table('tblProduksi')
            ->select('Pabrik as gudang', DB::raw('SUM(Produksi) as total_produksi'))
            ->whereBetween('Tanggal', [$startDate, $endDate])
            ->groupBy('Pabrik')
            ->orderBy('total_produksi', 'desc')
            ->get()
            ->toArray();
        
        // Jika tidak ada data produksi per pabrik, gunakan array kosong
        if (empty($produksiByPabrik)) {
            $produksiByPabrik = [];
        }
        
        // Format data
        $data = [
            'total_hari' => $totalDays,
            'hari_produksi' => $daysWithProduction,
            'efisiensi' => $efisiensi,
            'rata_produksi' => $rataProduksi,
            'produksi_gudang' => $produksiByPabrik
        ];
        
        // Dapatkan data produksi per jenis produk
        $produksiByJenis = DB::table('tblProduksi')
            ->join('tblJenisProduk', 'tblProduksi.KdJenis', '=', 'tblJenisProduk.KdJenis')
            ->select('tblJenisProduk.Jenis as jenis', DB::raw('SUM(tblProduksi.Produksi) as total_produksi'))
            ->whereBetween('tblProduksi.Tanggal', [$startDate, $endDate])
            ->groupBy('tblJenisProduk.Jenis')
            ->orderBy('total_produksi', 'desc')
            ->get();
        
        // Dapatkan data produksi per tahun produksi (jika ada kolom ThProduksi)
        $produksiByTahun = DB::table('tblProduksi')
            ->select('ThProduksi as tahun', DB::raw('SUM(Produksi) as total_produksi'))
            ->whereBetween('Tanggal', [$startDate, $endDate])
            ->groupBy('ThProduksi')
            ->orderBy('ThProduksi')
            ->get();
        
        // Return data laporan lengkap
        return [
            'title' => 'Laporan Kinerja Produksi',
            'jenis_laporan' => 'Laporan Kinerja Produksi',
            'tanggal_mulai' => $startDate->format('d F Y'),
            'tanggal_akhir' => $endDate->format('d F Y'),
            'data' => $data,
            'chart_data' => [
                'summary' => $data,
                'produksi_by_jenis' => $produksiByJenis,
                'produksi_by_tahun' => $produksiByTahun
            ],
            'total_hari' => $data['total_hari'],
            'hari_produksi' => $data['hari_produksi'],
            'efisiensi' => $data['efisiensi'],
            'rata_produksi' => $data['rata_produksi'],
            'produksi_gudang' => $data['produksi_gudang']
        ];
    } catch (\Exception $e) {
        // Log error untuk debugging
        Log::error('Error generating Kinerja Produksi report: ' . $e->getMessage());
        
        // Return data error
        return [
            'title' => 'Laporan Kinerja Produksi',
            'jenis_laporan' => 'Laporan Kinerja Produksi',
            'tanggal_mulai' => $startDate->format('d F Y'),
            'tanggal_akhir' => $endDate->format('d F Y'),
            'data' => [],
            'chart_data' => [],
            'error' => $e->getMessage()
        ];
    }
}
    
    /**
     * Render Produksi Per Kategori dalam PDF
     */
    private function renderProduksiPerKategoriPDF($pdf, $content)
    {
        // Tambahkan logo
        $logoPath = public_path('assets/images/logo1.png');
        if (file_exists($logoPath)) {
            $pdf->Image($logoPath, 10, 10, 30);
            $pdf->Ln(15);
        }
        
        // Tabel header
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(95, 10, 'Kategori', 1, 0, 'C');
        $pdf->Cell(95, 10, 'Total Produksi', 1, 1, 'C');
        
        // Tabel data
        $pdf->SetFont('Arial', '', 11);
        if (!empty($content['data'])) {
            foreach ($content['data'] as $item) {
                $pdf->Cell(95, 10, $item->kategori, 1, 0, 'L');
                $pdf->Cell(95, 10, number_format($item->total_produksi), 1, 1, 'R');
            }
        } else {
            $pdf->Cell(190, 10, 'Tidak ada data', 1, 1, 'C');
        }
    }
    
    /**
     * Render Analisis Produk Mingguan dalam PDF
     */
    private function renderAnalisisProdukMingguanPDF($pdf, $content)
    {
        // Tambahkan logo
        $logoPath = public_path('assets/images/logo1.png');
        if (file_exists($logoPath)) {
            $pdf->Image($logoPath, 10, 10, 30);
            $pdf->Ln(15);
        }
        
        if (!empty($content['data'])) {
            foreach ($content['data'] as $week => $products) {
                // Judul minggu
                $pdf->SetFont('Arial', 'B', 12);
                $pdf->Cell(0, 10, $week, 0, 1, 'L');
                
                // Tabel header
                $pdf->SetFont('Arial', 'B', 11);
                $pdf->Cell(95, 10, 'Produk', 1, 0, 'C');
                $pdf->Cell(95, 10, 'Total Produksi', 1, 1, 'C');
                
                // Tabel data
                $pdf->SetFont('Arial', '', 10);
                foreach ($products as $product => $total) {
                    $pdf->Cell(95, 8, $product, 1, 0, 'L');
                    $pdf->Cell(95, 8, number_format($total), 1, 1, 'R');
                }
                
                $pdf->Ln(5);
            }
        } else {
            $pdf->Cell(0, 10, 'Tidak ada data', 0, 1, 'C');
        }
    }
    
    /**
     * Render Stok Barang Bulanan dalam PDF
     */
    private function renderStokBarangBulananPDF($pdf, $content)
    {
        // Tambahkan logo
        $logoPath = public_path('assets/images/logo1.png');
        if (file_exists($logoPath)) {
            $pdf->Image($logoPath, 10, 10, 30);
            $pdf->Ln(15);
        }
        
        // Tabel header
        $pdf->SetFont('Arial', 'B', 11);
        $pdf->Cell(60, 10, 'Bulan', 1, 0, 'C');
        $pdf->Cell(65, 10, 'Kategori Produk', 1, 0, 'C');
        $pdf->Cell(65, 10, 'Total Stok', 1, 1, 'C');
        
        // Tabel data
        $pdf->SetFont('Arial', '', 10);
        if (!empty($content['data'])) {
            foreach ($content['data'] as $item) {
                $pdf->Cell(60, 8, $item->bulan, 1, 0, 'L');
                $pdf->Cell(65, 8, $item->kategori, 1, 0, 'L');
                $pdf->Cell(65, 8, number_format($item->total_produksi), 1, 1, 'R');
            }
        } else {
            $pdf->Cell(190, 10, 'Tidak ada data', 1, 1, 'C');
        }
    }
    
    /**
     * Render Penjualan Bulanan dalam PDF
     */
    private function renderPenjualanBulananPDF($pdf, $content)
    {
        // Tambahkan logo
        $logoPath = public_path('assets/images/logo1.png');
        if (file_exists($logoPath)) {
            $pdf->Image($logoPath, 10, 10, 30);
            $pdf->Ln(15);
        }
        
        if (!empty($content['data'])) {
            foreach ($content['data'] as $month => $data) {
                // Judul bulan
                $pdf->SetFont('Arial', 'B', 12);
                $pdf->Cell(0, 10, $month, 0, 1, 'L');
                
                // Produk
                $pdf->SetFont('Arial', 'B', 11);
                $pdf->Cell(0, 10, 'Berdasarkan Produk', 0, 1, 'L');
                
                // Tabel header produk
                $pdf->Cell(95, 10, 'Produk', 1, 0, 'C');
                $pdf->Cell(95, 10, 'Total Pengambilan', 1, 1, 'C');
                
                // Tabel data produk
                $pdf->SetFont('Arial', '', 10);
                foreach ($data['produk'] as $product => $total) {
                    $pdf->Cell(95, 8, $product, 1, 0, 'L');
                    $pdf->Cell(95, 8, number_format($total), 1, 1, 'R');
                }
                
                $pdf->Ln(5);
                
                // Pembeli
                $pdf->SetFont('Arial', 'B', 11);
                $pdf->Cell(0, 10, 'Berdasarkan Pembeli', 0, 1, 'L');
                
                // Tabel header pembeli
                $pdf->Cell(95, 10, 'Pembeli', 1, 0, 'C');
                $pdf->Cell(95, 10, 'Total Pengambilan', 1, 1, 'C');
                
                // Tabel data pembeli
                $pdf->SetFont('Arial', '', 10);
                foreach ($data['pembeli'] as $buyer => $total) {
                    $pdf->Cell(95, 8, $buyer, 1, 0, 'L');
                    $pdf->Cell(95, 8, number_format($total), 1, 1, 'R');
                }
                
                $pdf->AddPage();
            }
        } else {
            $pdf->Cell(0, 10, 'Tidak ada data', 0, 1, 'C');
        }
    }
    
    /**
     * Render Kinerja Produksi dalam PDF
     */
    private function renderKinerjaProduksiPDF($pdf, $content)
{
    // Tambahkan logo
    $logoPath = public_path('assets/images/logo1.png');
    if (file_exists($logoPath)) {
        $pdf->Image($logoPath, 10, 10, 30);
        $pdf->Ln(15);
    }
    
    // Summary cards
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(47.5, 10, 'Total Hari', 1, 0, 'C');
    $pdf->Cell(47.5, 10, 'Hari Produksi', 1, 0, 'C');
    $pdf->Cell(47.5, 10, 'Efisiensi', 1, 0, 'C');
    $pdf->Cell(47.5, 10, 'Rata-rata Produksi', 1, 1, 'C');
    
    $pdf->SetFont('Arial', '', 11);
    $pdf->Cell(47.5, 15, $content['total_hari'], 1, 0, 'C');
    $pdf->Cell(47.5, 15, $content['hari_produksi'], 1, 0, 'C');
    $pdf->Cell(47.5, 15, $content['efisiensi'] . '%', 1, 0, 'C');
    $pdf->Cell(47.5, 15, number_format($content['rata_produksi']), 1, 1, 'C');
    
    $pdf->Ln(10);
    
    // Produksi per Pabrik
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(0, 10, 'Produksi per Pabrik', 0, 1, 'L');
    
    // Tabel header
    $pdf->SetFont('Arial', 'B', 11);
    $pdf->Cell(95, 10, 'Pabrik', 1, 0, 'C');
    $pdf->Cell(95, 10, 'Total Produksi', 1, 1, 'C');
    
    // Tabel data
    $pdf->SetFont('Arial', '', 10);
    if (!empty($content['produksi_gudang'])) {
        foreach ($content['produksi_gudang'] as $item) {
            $pdf->Cell(95, 8, $item->gudang ?? $item['gudang'], 1, 0, 'L');
            $pdf->Cell(95, 8, number_format($item->total_produksi ?? $item['total_produksi']), 1, 1, 'R');
        }
    } else {
        $pdf->Cell(190, 10, 'Tidak ada data', 1, 1, 'C');
    }
    
    // Tambahkan informasi produksi per jenis produk jika tersedia
    if (!empty($content['chart_data']['produksi_by_jenis'])) {
        $pdf->Ln(10);
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(0, 10, 'Produksi per Jenis Produk', 0, 1, 'L');
        
        // Tabel header
        $pdf->SetFont('Arial', 'B', 11);
        $pdf->Cell(95, 10, 'Jenis Produk', 1, 0, 'C');
        $pdf->Cell(95, 10, 'Total Produksi', 1, 1, 'C');
        
        // Tabel data
        $pdf->SetFont('Arial', '', 10);
        foreach ($content['chart_data']['produksi_by_jenis'] as $item) {
            $pdf->Cell(95, 8, $item->jenis, 1, 0, 'L');
            $pdf->Cell(95, 8, number_format($item->total_produksi), 1, 1, 'R');
        }
    }
    
    // Tambahkan informasi produksi per tahun jika tersedia
    if (!empty($content['chart_data']['produksi_by_tahun'])) {
        $pdf->Ln(10);
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(0, 10, 'Produksi per Tahun', 0, 1, 'L');
        
        // Tabel header
        $pdf->SetFont('Arial', 'B', 11);
        $pdf->Cell(95, 10, 'Tahun', 1, 0, 'C');
        $pdf->Cell(95, 10, 'Total Produksi', 1, 1, 'C');
        
        // Tabel data
        $pdf->SetFont('Arial', '', 10);
        foreach ($content['chart_data']['produksi_by_tahun'] as $item) {
            $pdf->Cell(95, 8, $item->tahun, 1, 0, 'L');
            $pdf->Cell(95, 8, number_format($item->total_produksi), 1, 1, 'R');
        }
    }
}
    
    /**
     * Menghapus laporan.
     *
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