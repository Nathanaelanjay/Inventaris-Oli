<?php

namespace App\Helpers;

use App\Models\LogAktivitas;

class LogHelper
{
    public static function simpan($aktivitas, $tabel = null, $dataId = null)
    {
        if (auth()->check()) {

            LogAktivitas::create([
                'user_id' => auth()->user()->user_id,
                'aktivitas' => $aktivitas,
                'tabel_terkait' => $tabel,
                'data_id' => $dataId
            ]);

        }
    }
}