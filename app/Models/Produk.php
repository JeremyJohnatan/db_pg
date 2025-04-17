namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    use HasFactory;

    // Tentukan nama tabel jika berbeda dengan nama model
    protected $table = 'produk';  // Ganti dengan nama tabel yang sesuai

    // Tentukan kolom-kolom yang dapat diisi
    protected $fillable = ['kd_gudang', 'lokasi_gudang', 'pabrik'];
}
