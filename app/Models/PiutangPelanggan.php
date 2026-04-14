<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PiutangPelanggan extends Model
{
    protected $table = 'piutang_pelanggan';
    protected $primaryKey = 'piutang_id';

    protected $fillable = [
        'barang_keluar_id',
        'total_piutang',
        'sisa_piutang',
        'jumlah_bayar',
        'total_terbayar',
        'tanggal_transaksi',
        'tanggal_jatuh_tempo',
        'tanggal_bayar_terakhir',
        'status',
        'keterangan'
    ];

    public function barangKeluar()
    {
        return $this->belongsTo(BarangKeluar::class, 'barang_keluar_id');
    }
}