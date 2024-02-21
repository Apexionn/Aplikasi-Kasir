<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Genre extends Model
{
    use HasFactory;

    protected $guarded = ['id_genre'];
    protected $table = "Genre";

    protected $primaryKey = 'id_genre';
    public $timestamps = false;

    public function barangs()
    {
        return $this->hasMany(Barang::class, 'id_genre');
    }

    public function barangs2()
{
    return $this->belongsToMany(Barang::class, 'detail_genre', 'id_genre', 'id_barang');
}

    public function diskons()
    {
        return $this->belongsToMany(Diskon::class, 'detail_diskon', 'id_genre', 'id_diskon');
    }


}
