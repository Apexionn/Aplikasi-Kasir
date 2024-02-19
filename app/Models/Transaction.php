<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $guarded = ['id_transaction'];

    // protected $fillable = ['id_users', 'tanggal_transaksi'];
    // public $incrementing = true;
    protected $table = "Transaction";
    protected $primaryKey = 'id_transaction';
    public $timestamps = false;

    public function detailTransactions()
    {
        return $this->hasMany(DetailTransaction::class, 'id_transaction'); 
    }
}
