<!-- resources/views/laporan.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h2>Laporan Produksi Harian</h2>
            <h4>PG Rajawali I - Unit PG Notified Bari</h4>
        </div>
        
        <div class="card-body">
            <div class="data-tables datatable-dark">
                <table id="mauexport" class="table table-striped table-bordered">
                    <thead class="thead-light">
                        <tr>
                            <th>No</th>
                            <th>Jenis Produk</th>
                            <th>Jumlah Produksi</th>
                            <th>Satuan</th>
                            <th>Tanggal Produksi</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($produksi as $index => $item)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $item->jenis_produk }}</td>
                            <td>{{ number_format($item->jumlah, 0, ',', '.') }}</td>
                            <td>{{ $item->satuan }}</td>
                            <td>{{ $item->tanggal_produksi->format('d F Y') }}</td>
                            <td>
                                <span class="badge badge-{{ $item->status == 'selesai' ? 'success' : 'warning' }}">
                                    {{ ucfirst($item->status) }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        
        <div class="card-footer">
            <a href="{{ route('laporan.export') }}" class="btn btn-success">
                <i class="fas fa-file-export"></i> Export Data
            </a>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<!-- DataTables & Buttons -->
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.7.1/css/buttons.dataTables.min.css">

<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.7.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.print.min.js"></script>

<script>
$(document).ready(function() {
    $('#mauexport').DataTable({
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ],
        language: {
            url: '//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json'
        }
    });
});
</script>
@endsection