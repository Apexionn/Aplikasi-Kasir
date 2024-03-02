<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailTransaction extends Model
{
    use HasFactory;

    protected $fillable = ['id_transaction', 'id_barang', 'quantity', 'harga_jual', 'persentase_diskon'];
    protected $table = "detail_transaction";

    public $timestamps = false;

    public function transaction()
    {
        return $this->belongsTo(Transaction::class, 'id_transaction');
    }

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'id_barang');
    }

    public function calculateDiscountPercentage() {
        // Ensure there's a related barang and that both original price and sale price are set
        if (!$this->barang || !$this->harga_jual || !$this->barang->harga) {
            return 0;
        }

        $originalPrice = $this->barang->harga; // Assuming 'harga' is the original price in the Barang model
        $salePrice = $this->harga_jual;

        // Avoid division by zero
        if ($originalPrice == 0) {
            return 0;
        }

        $discountAmount = $originalPrice - $salePrice;
        $discountPercentage = ($discountAmount / $originalPrice) * 100;

        return round($discountPercentage, 2); // Round to 2 decimal places for readability
    }
}

