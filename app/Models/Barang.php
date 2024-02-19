<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    use HasFactory;

    protected $guarded = ['id_barang'];
    protected $table = "Barang";
    protected $primaryKey = 'id_barang';
    public $timestamps = false;

    public function genres() {
        return $this->belongsToMany(Genre::class, 'detail_genre', 'id_barang', 'id_genre');
    }

    public function detailGenres() {
        return $this->hasMany(Detail_Genre::class, 'id_barang');
    }

    public function genre() {
        return $this->belongsTo(Genre::class, 'id_genre');
    }

    public function findApplicableDiscount() {
        $today = now()->toDateString();

        $applicableDiscount = $this->genres()
            ->whereHas('diskons', function($query) use ($today) {
                $query->where('tanggal_mulai', '<=', $today)
                      ->where('tanggal_akhir', '>=', $today);
            })
            ->with(['diskons' => function($query) use ($today) {
                $query->where('tanggal_mulai', '<=', $today)
                      ->where('tanggal_akhir', '>=', $today)
                      ->orderBy('persentase_diskon', 'desc');
            }])
            ->get()
            ->pluck('diskons')
            ->collapse()
            ->unique('id_diskon')
            ->first();

        return $applicableDiscount;
    }
}
