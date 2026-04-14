<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    protected $table = 'produk';
    protected $primaryKey = 'produk_id';

    protected $fillable = [
        'kode_barang',
        'nama_barang',
        'stok',
        'stok_minimum',
        'harga_beli',
        'harga_jual',
        'kategori_id',
        'pemasok_id'
    ];

    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'kategori_id');
    }

    public function pemasok()
    {
        return $this->belongsTo(Pemasok::class, 'pemasok_id');
    }

    public function barangMasuk()
    {
        return $this->hasMany(BarangMasuk::class, 'produk_id');
    }

    public function barangKeluar()
    {
        return $this->hasMany(BarangKeluar::class, 'produk_id');
    }
}