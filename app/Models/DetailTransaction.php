<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailTransaction extends Model
{
    use HasFactory;

    protected $fillable = ['id_transaction', 'id_barang', 'quantity', 'harga_jual'];
    protected $table = "detail_transaction";

    public $timestamps = false;

    public function transaction()
    {
        return $this->belongsTo(Transaction::class, 'id_transaction'); 
    }
}
