<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BarangMasuk extends Model
{
    protected $table = 'barang_masuk';
    protected $primaryKey = 'barang_masuk_id';

    protected $fillable = [
        'tanggal',
        'jumlah',
        'harga_beli',
        'total',
        'user_id',
        'produk_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'produk_id');
    }

    public function hutang()
    {
        return $this->hasOne(HutangPembelian::class, 'barang_masuk_id');
    }

    public function pengeluaran()
    {
        return $this->hasOne(Pengeluaran::class, 'barang_masuk_id');
    }
}