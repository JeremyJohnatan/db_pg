<!-- resources/views/laporan/pdf.blade.php -->
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $content['title'] }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            font-size: 12px;
        }
        .report-header {
            text-align: center;
            margin-bottom: 30px;
        }
        .logo {
            max-width: 150px;
            height: auto;
            margin-bottom: 15px;
            display: block;
            margin-left: auto;
            margin-right: auto;
        }
        .report-header h2 {
            margin-bottom: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .card {
            border: 1px solid #ddd;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .card-header {
            background-color: #f2f2f2;
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }
        .card-body {
            padding: 15px;
        }
        .text-center {
            text-align: center;
        }
        .row {
            display: flex;
            flex-wrap: wrap;
            margin-right: -15px;
            margin-left: -15px;
        }
        .col-md-3, .col-md-6 {
            position: relative;
            width: 100%;
            padding-right: 15px;
            padding-left: 15px;
        }
        .col-md-3 {
            flex: 0 0 25%;
            max-width: 25%;
        }
        .col-md-6 {
            flex: 0 0 50%;
            max-width: 50%;
        }
        h5 {
            font-size: 14px;
            margin-top: 10px;
            margin-bottom: 10px;
        }
        h6 {
            font-size: 12px;
            margin-top: 8px;
            margin-bottom: 8px;
        }
        h2 {
            font-size: 18px;
            margin-top: 10px;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="report-header">
         <img src="{{ base_path('public/assets/images/logo1.png') }}" alt="Company Logo" class="logo">
        <h2>{{ $content['title'] }}</h2>
        <p>Periode: {{ $content['tanggal_mulai'] }} - {{ $content['tanggal_akhir'] }}</p>
    </div>

    <div class="report-content">
        @if($report->jenis_laporan == 'Laporan Produksi Per Kategori')
            <table>
                <thead>
                    <tr>
                        <th>Kategori</th>
                        <th>Total Produksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($content['data'] as $item)
                        <tr>
                            <td>{{ $item->kategori }}</td>
                            <td>{{ number_format($item->total_produksi) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            
        @elseif($report->jenis_laporan == 'Laporan Analisis Produk Mingguan')
            @foreach($content['data'] as $week => $products)
                <div class="card">
                    <div class="card-header">
                        <h5>{{ $week }}</h5>
                    </div>
                    <div class="card-body">
                        <table>
                            <thead>
                                <tr>
                                    <th>Produk</th>
                                    <th>Total Produksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($products as $product => $total)
                                    <tr>
                                        <td>{{ $product }}</td>
                                        <td>{{ number_format($total) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endforeach
            
        @elseif($report->jenis_laporan == 'Laporan Stok Barang Bulanan')
                <table>
                    <thead>
                        <tr>
                            <th>Bulan</th>
                            <th>Kategori</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($content['chart_data'] as $item)
                        <tr>
                            <td>{{ $item['bulan'] }}</td>
                            <td>{{ $item['kategori'] }}</td>
                            <td>{{ number_format($item['total']) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            
        @elseif($report->jenis_laporan == 'Laporan Penjualan Bulanan')
            @foreach($content['data'] as $month => $data)
                <div class="card">
                    <div class="card-header">
                        <h5>{{ $month }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h6>Berdasarkan Produk</h6>
                                <table>
                                    <thead>
                                        <tr>
                                            <th>Produk</th>
                                            <th>Total Pengambilan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($data['produk'] as $product => $total)
                                            <tr>
                                                <td>{{ $product }}</td>
                                                <td>{{ number_format($total) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <h6>Berdasarkan Pembeli</h6>
                                <table>
                                    <thead>
                                        <tr>
                                            <th>Pembeli</th>
                                            <th>Total Pengambilan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($data['pembeli'] as $buyer => $total)
                                            <tr>
                                                <td>{{ $buyer }}</td>
                                                <td>{{ number_format($total) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
            
        @elseif($report->jenis_laporan == 'Laporan Kinerja Produksi')
            <div class="row">
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-body text-center">
                            <h5>Total Hari</h5>
                            <h2>{{ $content['total_hari'] }}</h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-body text-center">
                            <h5>Hari Produksi</h5>
                            <h2>{{ $content['hari_produksi'] }}</h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-body text-center">
                            <h5>Efisiensi</h5>
                            <h2>{{ $content['efisiensi'] }}%</h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-body text-center">
                            <h5>Rata-rata Produksi</h5>
                            <h2>{{ number_format($content['rata_produksi']) }}</h2>
                        </div>
                    </div>
                </div>
            </div>
            
            <h5 style="margin-top: 20px;">Produksi per Gudang</h5>
            <table>
                <thead>
                    <tr>
                        <th>Gudang</th>
                        <th>Total Produksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($content['produksi_gudang'] as $item)
                        <tr>
                            <td>{{ $item->gudang }}</td>
                            <td>{{ number_format($item->total_produksi) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p>Tidak ada data yang tersedia untuk jenis laporan ini.</p>
        @endif
    </div>
</body>
</html>