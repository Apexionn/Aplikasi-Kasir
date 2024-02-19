<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Detail_Genre extends Model
{
    use HasFactory;

    protected $table = 'detail_genre';

    public function game()
    {
        return $this->belongsTo(Barang::class, 'id_barang', 'id_barang');
    }

    public function genre()
    {
        return $this->belongsTo(Genre::class, 'id_genre', 'id_genre');
    }



}
