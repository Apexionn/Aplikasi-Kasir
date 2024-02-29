<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $guarded = ['id_transaction'];

    protected $table = "Transaction";
    protected $primaryKey = 'id_transaction';
    public $timestamps = false;

    public function detailTransactions()
    {
        return $this->hasMany(DetailTransaction::class, 'id_transaction');
    }

        public function user()
    {
        return $this->belongsTo(User::class, 'id_users');
    }

}
