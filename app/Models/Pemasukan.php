<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pemasukan extends Model
{
    protected $table = 'pemasukan';
    protected $primaryKey = 'pemasukan_id';

    protected $fillable = [
        'jumlah',
        'tanggal',
        'keterangan',
        'barang_keluar_id'
    ];

    public function barangKeluar()
    {
        return $this->belongsTo(BarangKeluar::class, 'barang_keluar_id');
    }
}