<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pemasok extends Model
{
    protected $table = 'pemasok';
    protected $primaryKey = 'pemasok_id';

    public $timestamps = false;

    protected $fillable = [
        'nama_pemasok',
        'alamat',
        'kontak',
        'email'
    ];

    public function produk()
    {
        return $this->hasMany(Produk::class, 'pemasok_id');
    }
}