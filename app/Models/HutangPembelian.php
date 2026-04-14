<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HutangPembelian extends Model
{
    protected $table = 'hutang_pembelian';
    protected $primaryKey = 'hutang_id';

    protected $fillable = [
        'barang_masuk_id',
        'total_hutang',
        'sisa_hutang',
        'jumlah_bayar',
        'total_terbayar',
        'tanggal_transaksi',
        'tanggal_jatuh_tempo',
        'tanggal_bayar_terakhir',
        'status',
        'keterangan'
    ];

    public function barangMasuk()
    {
        return $this->belongsTo(BarangMasuk::class, 'barang_masuk_id');
    }
}