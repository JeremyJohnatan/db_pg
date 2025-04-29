<!-- resources/views/laporan/print.blade.php -->
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $content['title'] }}</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        .report-header {
            text-align: center;
            margin-bottom: 30px;
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
        .chart-container {
            margin: 20px 0;
            height: 300px;
        }
        @media print {
            .no-print {
                display: none;
            }
            body {
                margin: 0;
                padding: 15px;
            }
        }
    </style>
</head>
<body>
    <div class="no-print" style="text-align: right; margin-bottom: 20px;">
        <button onclick="window.print()" class="btn btn-primary">
            <i class="fas fa-print"></i> Cetak
        </button>
    </div>

    <div class="report-container">
        <div class="report-header">
            <h2>{{ $content['title'] }}</h2>
            <p>Periode: {{ $content['tanggal_mulai'] }} - {{ $content['tanggal_akhir'] }}</p>
        </div>
    
        <div class="report-content">
            @if($report->jenis_laporan == 'Laporan Produksi Per Kategori')
                <div class="table-responsive">
                    <table class="table table-bordered">
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
                </div>
                
                @if(isset($content['chart_data']) && count($content['chart_data']) > 0)
                <div class="chart-container">
                    <canvas id="chartProduksiKategori"></canvas>
                </div>
                
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        const ctx = document.getElementById('chartProduksiKategori').getContext('2d');
                        const chartData = @json($content['chart_data']);
                        
                        new Chart(ctx, {
                            type: 'pie',
                            data: {
                                labels: chartData.map(item => item.kategori),
                                datasets: [{
                                    data: chartData.map(item => item.total),
                                    backgroundColor: [
                                        '#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b', '#858796',
                                        '#5a5c69', '#6610f2', '#fd7e14', '#20c9a6', '#6f42c1', '#6f42c1'
                                    ]
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                legend: {
                                    position: 'right'
                                }
                            }
                        });
                    });
                </script>
                @endif
                
            @elseif($report->jenis_laporan == 'Laporan Analisis Produk Mingguan')
                @foreach($content['data'] as $week => $products)
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5>{{ $week }}</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Produk</th>
                                            <th>Total Produksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($products as $product => $total)
                                            <tr>
                                                <td>{{ $