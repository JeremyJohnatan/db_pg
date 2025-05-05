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

        .report-actions button.btn-primary, .report-actions a.btn-primary {
            background-color: #004a94;
            color: white;
            border: none;
            border-radius: 8px;
            padding: 10px 15px;
            cursor: pointer;
            font-size: 0.9rem;
            transition: background-color 0.3s;
            text-decoration: none;
        }

        .report-actions button.btn-primary:hover, .report-actions a.btn-primary:hover {
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

        /* Tambahan style untuk dropdown profile */
        .profile-dropdown {
            position: relative;
        }

        .profile-dropdown .dropdown-menu {
            right: 0;
            left: auto;
        }
        .pagination {
            font-size: 0.9rem;
        }
        .page-item .page-link {
            padding: 0.375rem 0.75rem;
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
                    <a href="{{ route('laporan.download-all') }}" class="btn btn-primary" id="downloadAllReports">
                        <i class="fas fa-download"></i> Download Semua Laporan
                    </a>
                </div>
            </div>
            <div class="report-table-container">
                <table class="report-table">
                    <thead>
                        <tr>
                            <th>No</th>
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
                            <td>{{ $reports->firstItem() + $index }}</td>
                            <td>{{ $report->jenis_laporan }}</td>
                            <td>{{ $report->created_at->format('d F Y') }}</td>
                            <td>{{ $report->status }}</td>
                            <td>
                                <div class="action-buttons">
                                    <a href="{{ route('laporan.download', $report->id) }}" class="btn btn-sm btn-outline-primary download-btn">
                                        <i class="fas fa-file-pdf"></i> Unduh PDF
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
                <div class="d-flex justify-content-center mt-4">
                    {{ $reports->links() }}
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
        const routeDownloadReport = "{{ url('laporan/download') }}";
        const logoUrl = "{{ url('assets/images/logo1.png') }}";
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/js/all.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const navLinks = document.querySelectorAll('.sidebar .nav-link');
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

            // Tambahkan event listener untuk tombol "Download Semua Laporan"
            document.getElementById('downloadAllReports').addEventListener('click', function(e) {
                // Tampilkan loading message atau spinner jika diinginkan
                alert('Sedang mengunduh semua laporan, harap tunggu...');
            });
        });
    </script>
@endsection