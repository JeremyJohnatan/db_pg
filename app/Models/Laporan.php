<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Laporan extends Model
{
    protected $table = 'tblProduksi'; // Sesuaikan dengan tabel yang menyimpan data produksi
    
    /**
     * Relasi ke tabel jenis produk
     */
    public function jenisProduk()
    {
        return $this->belongsTo('App\Models\JenisProduk', 'jenis_produk_id');
    }
    
    /**
     * URL untuk preview laporan
     */
    // public function getPreviewUrl()
    // {
    //     return route('laporan.preview', ['id' => $this->id]);
    // }
    
    /**
     * URL untuk download laporan
     */
    public function getDownloadUrl()
    {
        return route('laporan.download', ['id' => $this->id]);
    }
    
    /**
     * URL untuk export data (sudah ada di view)
     */
    public function getExportUrl()
    {
        return route('laporan.export');
    }
    
    /**
     * Format jumlah produksi dengan separator ribuan
     */
    public function getFormattedJumlahAttribute()
    {
        return number_format($this->jumlah, 0, ',', '.');
    }
    
    /**
     * Format tanggal produksi
     */
    public function getFormattedTanggalAttribute()
    {
        return $this->tanggal_produksi->format('d F Y');
    }
    
    /**
     * Warna badge berdasarkan status
     */
    public function getStatusBadgeAttribute()
    {
        $status = strtolower($this->status);
        $badgeClass = [
            'selesai' => 'success',
            'proses' => 'warning',
            'batal' => 'danger'
        ];
        
        return $badgeClass[$status] ?? 'secondary';
    }
}