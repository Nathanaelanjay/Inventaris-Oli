<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengeluaran extends Model
{
    protected $table = 'pengeluaran';
    protected $primaryKey = 'pengeluaran_id';

    protected $fillable = [
        'jumlah',
        'tanggal',
        'keterangan',
        'barang_masuk_id'
    ];

    public function barangMasuk()
    {
        return $this->belongsTo(BarangMasuk::class, 'barang_masuk_id');
    }
}