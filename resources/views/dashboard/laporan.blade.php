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
                        <button class="btn btn-primary" id="downloadAllReports">Download Semua Laporan</button>
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
                                                <a href="{{ route('reports.download', $report->id) }}" class="btn btn-sm btn-outline-success download-btn">
                                                    <i class="fas fa-download"></i> Unduh
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <!-- Data statis sebagai contoh jika $reports tidak ada -->
                                <tr>
                                    <td>1</td>
                                    <td>Laporan Produksi Per Kategori</td>
                                    <td>14 April 2025</td>
                                    <td>Selesai</td>
                                    <td>
                                        <div class="action-buttons">
                                            <button class="btn btn-sm btn-outline-primary preview-btn">
                                                <i class="fas fa-eye"></i> Preview
                                            </button>
                                            <a href="#" class="btn btn-sm btn-outline-success download-btn" id="10">
                                                <i class="fas fa-download"></i> Unduh
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>2</td>
                                    <td>Laporan Analisis Produk Mingguan</td>
                                    <td>12 April 2025</td>
                                    <td>Selesai</td>
                                    <td>
                                        <div class="action-buttons">
                                            <button class="btn btn-sm btn-outline-primary preview-btn" data-report="2">
                                                <i class="fas fa-eye"></i> Preview
                                            </button>
                                            <a href="#" class="btn btn-sm btn-outline-success download-btn" data-report="2">
                                                <i class="fas fa-download"></i> Unduh
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>3</td>
                                    <td>Laporan Stok Barang Bulanan</td>
                                    <td>10 April 2025</td>
                                    <td>Selesai</td>
                                    <td>
                                        <div class="action-buttons">
                                            <button class="btn btn-sm btn-outline-primary preview-btn" data-report="3">
                                                <i class="fas fa-eye"></i> Preview
                                            </button>
                                            <a href="#" class="btn btn-sm btn-outline-success download-btn" data-report="3">
                                                <i class="fas fa-download"></i> Unduh
                                            </a>
                                        </div>
                                    </td>
                                </tr>
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
                    <span id="produksi"></span>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="button" class="btn btn-primary" id="printFromPreview">Cetak</button>
                    <button type="button" class="btn btn-success" id="downloadFromPreview">Unduh</button>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script>
        const routeLaporan = "{{ route('dashboard.laporan') }}";
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/js/all.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const navLinks = document.querySelectorAll('.sidebar .nav-link');
            const previewModal = new bootstrap.Modal(document.getElementById('previewModal'));

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

            document.querySelectorAll('.download-btn').forEach(button => {
                button.addEventListener('click', function (e) {
                    e.preventDefault();
                    const id = this.id || this.getAttribute('data-report');
                    console.log('id dari button : ', id)

                    // In a real application, you would initiate a download here
                    alert(`Mengunduh laporan #${id}...`);
                    // Example: window.location.href = `/reports/${id}/download`;
                    
                    // Or you could fetch data as was done with the detail button previously
                    // fetch("/laporan/download/" + id)
                    // .then(response => response.blob())
                    // .then(blob => {
                    //     const url = window.URL.createObjectURL(blob);
                    //     const a = document.createElement('a');
                    //     a.href = url;
                    //     a.download = `Laporan-${id}.pdf`;
                    //     document.body.appendChild(a);
                    //     a.click();
                    //     a.remove();
                    // });
                });
            });

            const filterTanggalBtn = document.getElementById('filter-tanggal');
            if (filterTanggalBtn) {
                filterTanggalBtn.addEventListener('click', function () {
                    const tanggalMulai = document.getElementById('tanggal-mulai').value;
                    const tanggalAkhir = document.getElementById('tanggal-akhir').value;

                    if (!tanggalMulai || !tanggalAkhir) {
                        alert('Silakan pilih tanggal mulai dan tanggal akhir');
                        return;
                    }

                    window.location.href = `${routeLaporan}?tanggal_mulai=${tanggalMulai}&tanggal_akhir=${tanggalAkhir}`;
                });
            }

            // Preview button functionality
            document.querySelectorAll('.preview-btn').forEach(btn => {
                btn.addEventListener('click', function () {
                
                    const reportId = this.getAttribute('data-report');

                    // dd($data)

                    // In a real application, you would fetch the report content here
                    // For demo purposes, we'll use a placeholder
                    // const reportContent = `<h1>Preview Laporan #${reportId}</h1><p>Ini adalah konten preview untuk laporan ${reportId}.</p>`;

                    // const previewFrame = document.getElementById('previewFrame');
                    // previewFrame.srcdoc = reportContent;

                    // Set the modal title
                    // document.getElementById('previewModalLabel').textContent = `Preview Laporan #${reportId}`;

                    // Set data attributes for print and download from preview
                    // previewFrame.setAttribute('data-report-id', reportId);

                    previewModal.show();
                });
            });

            // Print button functionality
            document.querySelectorAll('.print-btn').forEach(btn => {
                btn.addEventListener('click', function () {
                    const reportId = this.getAttribute('data-report');
                    // In a real application, you would open a print dialog for the report
                    alert(`Mencetak laporan #${reportId}...`);
                    // Example: window.open(`/reports/${reportId}/print`, '_blank');
                });
            });

            // Download all reports button
            document.getElementById('downloadAllReports').addEventListener('click', function () {
                alert('Mengunduh semua laporan dalam format ZIP...');
                // In a real application, you would initiate a bulk download
                // Example: window.location.href = '/reports/download-all';
            });

            // Print from preview
            document.getElementById('printFromPreview').addEventListener('click', function () {
                const reportId = document.getElementById('previewFrame')?.getAttribute('data-report-id');
                alert(`Mencetak laporan #${reportId} dari preview...`);
                // In a real application, you would print the iframe content
                // Example: document.getElementById('previewFrame').contentWindow.print();
            });

            // Download from preview
            document.getElementById('downloadFromPreview').addEventListener('click', function () {
                const reportId = document.getElementById('previewFrame')?.getAttribute('data-report-id');
                alert(`Mengunduh laporan #${reportId} dari preview...`);
                // In a real application, you would initiate download
                // Example: window.location.href = `/reports/${reportId}/download`;
            });
        });
    </script>
@endsection