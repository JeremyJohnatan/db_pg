@extends('layouts.app')

@section('title', 'Laporan | PG Rajawali I')

@section('styles')
<style>
      .content-area {
            padding: 20px;
        }

        .report-card {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            padding: 20px;
        }

        .report-card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .report-card-title {
            color: #333;
            font-size: 1.2rem;
            font-weight: bold;
        }

        .report-actions button.btn-primary {
            background-color: #004a94;
            color: white;
            border: none;
            border-radius: 8px;
            padding: 10px 15px;
            cursor: pointer;
            font-size: 0.9rem;
            transition: background-color 0.3s;
        }

        .report-actions button.btn-primary:hover {
            background-color: #003366;
        }

        .report-table-container {
            overflow-x: auto;
        }

        .report-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .report-table th,
        .report-table td {
            border: 1px solid #e0e0e0;
            padding: 10px;
            text-align: left;
            font-size: 0.9rem;
        }

        .report-table th {
            background-color: #f5f7fb;
            font-weight: bold;
            color: #333;
        }

        .no-data {
            color: #777;
            font-style: italic;
        }

        .user-avatar {
            width: 32px;
            height: 32px;
            background-color: #004a94;
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            cursor: pointer;
        }

        .action-buttons {
            display: flex;
            gap: 5px;
        }

        .action-buttons .btn {
            padding: 5px 8px;
            font-size: 0.8rem;
        }

        /* Modal styles for preview */
        .modal-preview {
            max-width: 90%;
        }

        .modal-preview .modal-body {
            height: 70vh;
        }

        .modal-preview iframe {
            width: 100%;
            height: 100%;
            border: none;
        }

        /* Tambahan style untuk dropdown profile */
        .profile-dropdown {
            position: relative;
        }

        .profile-dropdown .dropdown-menu {
            right: 0;
            left: auto;
        }
    </style>
@endsection

@section('content')
    <div class="content-area">
        <div class="report-card">
            <div class="report-card-header">
                <h5 class="report-card-title">Laporan</h5>
                <div class="report-actions">
                    <button class="btn btn-success" id="createReportBtn" data-bs-toggle="modal" data-bs-target="#createReportModal">
                        <i class="fas fa-plus-circle"></i> Buat Laporan
                    </button>
                    <button class="btn btn-primary" id="downloadAllReports">
                        <i class="fas fa-download"></i> Download Semua Laporan
                    </button>
                </div>
            </div>
            <div class="report-table-container">
                <table class="report-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Jenis Laporan</th>
                            <th>Tanggal Dibuat</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(isset($reports) && count($reports) > 0)
                        @foreach($reports as $index => $report)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $report->jenis_laporan }}</td>
                            <td>{{ $report->created_at->format('d F Y') }}</td>
                            <td>{{ $report->status }}</td>
                            <td>
                                <div class="action-buttons">
                                    <button class="btn btn-sm btn-outline-primary preview-btn" data-report="{{ $report->id }}">
                                        <i class="fas fa-eye"></i> Preview
                                    </button>
                                    <a href="{{ route('laporan.download', $report->id) }}" class="btn btn-sm btn-outline-success download-btn">
                                        <i class="fas fa-download"></i> Unduh
                                    </a>
                                    <form action="{{ route('laporan.destroy', $report->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus laporan ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger delete-btn">
                                            <i class="fas fa-trash"></i> Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                        @endif
                        <!-- Pesan "Tidak ada data" dengan class no-data-row untuk JavaScript -->
                        <tr class="no-data-row" style="display: none;">
                            <td colspan="5" class="text-center no-data">Tidak ada data laporan yang tersedia.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Preview Modal -->
    <div class="modal fade" id="previewModal" tabindex="-1" aria-labelledby="previewModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-preview">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="previewModalLabel">Preview Laporan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="reportPreviewContent"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="button" class="btn btn-primary" id="printFromPreview">Cetak</button>
                    <button type="button" class="btn btn-success" id="downloadFromPreview">Unduh</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Create Report Modal -->
    <div class="modal fade" id="createReportModal" tabindex="-1" aria-labelledby="createReportModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createReportModalLabel">Buat Laporan Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="createReportForm" method="POST" action="{{ route('laporan.store') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="reportType" class="form-label">Jenis Laporan</label>
                            <select class="form-select" id="reportType" name="jenis_laporan" required>
                                <option value="" selected disabled>Pilih Jenis Laporan</option>
                                <option value="Laporan Produksi Per Kategori">Laporan Produksi Per Kategori</option>
                                <option value="Laporan Analisis Produk Mingguan">Laporan Analisis Produk Mingguan</option>
                                <option value="Laporan Stok Barang Bulanan">Laporan Stok Barang Bulanan</option>
                                <option value="Laporan Penjualan Bulanan">Laporan Penjualan Bulanan</option>
                                <option value="Laporan Kinerja Produksi">Laporan Kinerja Produksi</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="startDate" class="form-label">Tanggal Mulai</label>
                            <input type="date" class="form-control" id="startDate" name="tanggal_mulai" required>
                        </div>
                        <div class="mb-3">
                            <label for="endDate" class="form-label">Tanggal Akhir</label>
                            <input type="date" class="form-control" id="endDate" name="tanggal_akhir" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" id="submitCreateReport">Buat Laporan</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        const routeLaporan = "{{ route('dashboard.laporan') }}";
        // Ubah definisi route berikut
        const routePreviewReport = "{{ url('laporan/preview') }}";
        const routeDownloadReport = "{{ url('laporan/download') }}";
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/js/all.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const navLinks = document.querySelectorAll('.sidebar .nav-link');
            const previewModal = new bootstrap.Modal(document.getElementById('previewModal'));
            const createReportModal = new bootstrap.Modal(document.getElementById('createReportModal'));
            
            // Set default date values for create report form
            const today = new Date();
            const firstDayOfMonth = new Date(today.getFullYear(), today.getMonth(), 1);
            
            document.getElementById('endDate').valueAsDate = today;
            document.getElementById('startDate').valueAsDate = firstDayOfMonth;

            navLinks.forEach(link => {
                link.addEventListener('click', function () {
                    navLinks.forEach(l => l.classList.remove('active'));
                    this.classList.add('active');
                });
            });

            // Fungsi untuk memeriksa apakah ada baris laporan dan mengatur pesan "tidak ada data"
            function toggleNoDataMessage() {
                const reportTable = document.querySelector('.report-table tbody');
                const reportRows = reportTable.querySelectorAll('tr:not(.no-data-row)');
                const noDataRow = reportTable.querySelector('tr.no-data-row');
                
                // Jika ada baris laporan (selain baris "tidak ada data")
                if (reportRows.length > 0) {
                    // Sembunyikan pesan "tidak ada data" jika ada
                    if (noDataRow) {
                        noDataRow.style.display = 'none';
                    }
                } else {
                    // Tampilkan pesan "tidak ada data" jika ada
                    if (noDataRow) {
                        noDataRow.style.display = 'table-row';
                    } else {
                        // Buat baris "tidak ada data" jika belum ada
                        const newNoDataRow = document.createElement('tr');
                        newNoDataRow.className = 'no-data-row';
                        newNoDataRow.innerHTML = '<td colspan="5" class="text-center no-data">Tidak ada data laporan yang tersedia.</td>';
                        reportTable.appendChild(newNoDataRow);
                    }
                }
            }
            
            // Panggil fungsi saat halaman dimuat
            toggleNoDataMessage();

            // Submit create report form
            document.getElementById('submitCreateReport').addEventListener('click', function() {
                const form = document.getElementById('createReportForm');
                
                // Validasi form sebelum submit
                if (!form.checkValidity()) {
                    form.reportValidity();
                    return;
                }
                
                // Submit form
                form.submit();
            });

            // Preview button functionality
            document.querySelectorAll('.preview-btn').forEach(btn => {
                btn.addEventListener('click', function () {
                    const reportId = this.getAttribute('data-report');
                    
                    // Tampilkan loading indicator
                    document.getElementById('reportPreviewContent').innerHTML = '<div class="text-center"><div class="spinner-border" role="status"></div><p>Memuat preview laporan...</p></div>';
                    
                    // Fetch report preview content - PERBAIKAN URL disini
                    fetch(`${routePreviewReport}/${reportId}`)
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Gagal memuat preview laporan');
                            }
                            return response.json();
                        })
                        .then(data => {
                            // Set the modal content
                            let previewContent = '';
                            
                            // Buat konten preview berdasarkan jenis laporan
                            if (data.jenis_laporan === 'Laporan Produksi Per Kategori') {
                                previewContent = createProductionCategoryPreview(data);
                            } else if (data.jenis_laporan === 'Laporan Analisis Produk Mingguan') {
                                previewContent = createWeeklyProductAnalysisPreview(data);
                            } else if (data.jenis_laporan === 'Laporan Stok Barang Bulanan') {
                                previewContent = createMonthlyStockPreview(data);
                            } else if (data.jenis_laporan === 'Laporan Penjualan Bulanan') {
                                previewContent = createMonthlySalesPreview(data);
                            } else if (data.jenis_laporan === 'Laporan Kinerja Produksi') {
                                previewContent = createProductionPerformancePreview(data);
                            } else {
                                previewContent = '<div class="alert alert-info">Tipe laporan tidak dikenali.</div>';
                            }
                            
                            document.getElementById('reportPreviewContent').innerHTML = previewContent;
                            
                            // Set data attributes for buttons
                            document.getElementById('downloadFromPreview').setAttribute('data-report-id', reportId);
                            document.getElementById('printFromPreview').setAttribute('data-report-id', reportId);
                        })
                        .catch(error => {
                            document.getElementById('reportPreviewContent').innerHTML = `<div class="alert alert-danger">${error.message}</div>`;
                        });
                    
                    previewModal.show();
                });
            });

            // Rest of your JS code remains the same
            // ...

            // Download from preview
            document.getElementById('downloadFromPreview').addEventListener('click', function () {
                const reportId = this.getAttribute('data-report-id');
                // PERBAIKAN URL disini
                window.location.href = `${routeDownloadReport}/${reportId}`;
            });

            // Print from preview
            document.getElementById('printFromPreview').addEventListener('click', function () {
                const reportId = this.getAttribute('data-report-id');
                
                // Open print window - PERBAIKAN URL disini
                const printWindow = window.open(`{{ url('laporan/print') }}/${reportId}`, '_blank');
                
                // Focus on the new window
                if (printWindow) {
                    printWindow.focus();
                }
            });

            // Helper functions untuk membuat preview content
            function createProductionCategoryPreview(data) {
                return `
                    <div class="report-header mb-4">
                        <h4>${data.jenis_laporan}</h4>
                        <p>Periode: ${data.tanggal_mulai} - ${data.tanggal_akhir}</p>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Kategori</th>
                                    <th>Total Produksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                ${data.data.map(item => `
                                    <tr>
                                        <td>${item.kategori}</td>
                                        <td>${item.total_produksi}</td>
                                    </tr>
                                `).join('')}
                            </tbody>
                        </table>
                    </div>
                `;
            }
            
            function createWeeklyProductAnalysisPreview(data) {
                return `
                    <div class="report-header mb-4">
                        <h4>${data.jenis_laporan}</h4>
                        <p>Periode: ${data.tanggal_mulai} - ${data.tanggal_akhir}</p>
                    </div>
                    ${Object.keys(data.data).map(week => `
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5>${week}</h5>
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
                                            ${Object.keys(data.data[week]).map(product => `
                                                <tr>
                                                    <td>${product}</td>
                                                    <td>${data.data[week][product]}</td>
                                                </tr>
                                            `).join('')}
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    `).join('')}
                `;
            }
            
            function createMonthlyStockPreview(data) {
                return `
                    <div class="report-header mb-4">
                        <h4>${data.jenis_laporan}</h4>
                        <p>Periode: ${data.tanggal_mulai} - ${data.tanggal_akhir}</p>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Produk</th>
                                    <th>Total Produksi</th>
                                    <th>Total Pengambilan</th>
                                    <th>Stok Akhir</th>
                                </tr>
                            </thead>
                            <tbody>
                                ${data.data.map(item => `
                                    <tr>
                                        <td>${item.produk}</td>
                                        <td>${item.total_produksi}</td>
                                        <td>${item.total_pengambilan}</td>
                                        <td>${item.stok_akhir}</td>
                                    </tr>
                                `).join('')}
                            </tbody>
                        </table>
                    </div>
                `;
            }
            
            function createMonthlySalesPreview(data) {
                return `
                    <div class="report-header mb-4">
                        <h4>${data.jenis_laporan}</h4>
                        <p>Periode: ${data.tanggal_mulai} - ${data.tanggal_akhir}</p>
                    </div>
                    ${Object.keys(data.data).map(month => `
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5>${month}</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h6>Berdasarkan Produk</h6>
                                        <div class="table-responsive">
                                            <table class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>Produk</th>
                                                        <th>Total Pengambilan</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    ${Object.keys(data.data[month].produk).map(product => `
                                                        <tr>
                                                            <td>${product}</td>
                                                            <td>${data.data[month].produk[product]}</td>
                                                        </tr>
                                                    `).join('')}
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <h6>Berdasarkan Pembeli</h6>
                                        <div class="table-responsive">
                                            <table class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>Pembeli</th>
                                                        <th>Total Pengambilan</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    ${Object.keys(data.data[month].pembeli).map(buyer => `
                                                        <tr>
                                                            <td>${buyer}</td>
                                                            <td>${data.data[month].pembeli[buyer]}</td>
                                                        </tr>
                                                    `).join('')}
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `).join('')}
                `;
            }
            
            function createProductionPerformancePreview(data) {
                return `
                    <div class="report-header mb-4">
                        <h4>${data.jenis_laporan}</h4>
                        <p>Periode: ${data.tanggal_mulai} - ${data.tanggal_akhir}</p>
                    </div>
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="card">
                                <div class="card-body text-center">
                                    <h5 class="card-title">Total Hari</h5>
                                    <h2>${data.total_hari}</h2>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card">
                                <div class="card-body text-center">
                                    <h5 class="card-title">Hari Produksi</h5>
                                    <h2>${data.hari_produksi}</h2>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card">
                                <div class="card-body text-center">
                                    <h5 class="card-title">Efisiensi</h5>
                                    <h2>${data.efisiensi}%</h2>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card">
                                <div class="card-body text-center">
                                    <h5 class="card-title">Rata-rata Produksi</h5>
                                    <h2>${data.rata_produksi}</h2>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="table-responsive">
                        <h5>Produksi per Gudang</h5>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Gudang</th>
                                    <th>Total Produksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                ${data.produksi_gudang.map(item => `
                                    <tr>
                                        <td>${item.gudang}</td>
                                        <td>${item.total_produksi}</td>
                                    </tr>
                                `).join('')}
                            </tbody>
                        </table>
                    </div>
                `;
            }
        });
    </script>
@endsection