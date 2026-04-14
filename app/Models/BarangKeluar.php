<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BarangKeluar extends Model
{
    protected $table = 'barang_keluar';
    protected $primaryKey = 'barang_keluar_id';

    protected $fillable = [
        'tanggal',
        'jumlah',
        'harga_jual',
        'total',
        'produk_id',
        'user_id',
        'pelanggan_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'produk_id');
    }

    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class, 'pelanggan_id');
    }

    public function piutang()
    {
        return $this->hasOne(PiutangPelanggan::class, 'barang_keluar_id');
    }

    public function pemasukan()
    {
        return $this->hasOne(Pemasukan::class, 'barang_keluar_id');
    }
}