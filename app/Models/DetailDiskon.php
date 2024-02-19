<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailDiskon extends Model
{
    use HasFactory;

    protected $table = 'detail_diskon';
    public $timestamps = false;


        public function diskon()
        {
            return $this->belongsTo(Diskon::class, 'id_diskon');
        }

        public function genre()
        {
            return $this->belongsTo(Genre::class, 'id_genre');
        }
}
