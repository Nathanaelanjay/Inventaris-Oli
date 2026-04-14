<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pelanggan extends Model
{
    protected $table = 'pelanggan';
    protected $primaryKey = 'pelanggan_id';

    protected $fillable = [
        'nama_bengkel',
        'no_telp',
        'alamat'
    ];

    public function barangKeluar()
    {
        return $this->hasMany(BarangKeluar::class, 'pelanggan_id');
    }
}