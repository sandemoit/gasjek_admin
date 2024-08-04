<?php

namespace App\Controllers;

class Laporan extends BaseController
{
    public function __construct()
    {
        session();
    }

    public function index()
    {
        // Laporan Pemasukan GASJek:
        // 1. Total Pemasukan Bersih Food: Hari, Bulan, Tahun, dan Custom | harga asli - harga up + 1000
        // 2. Total Pemasukan Bersih Ride: Hari, Bulan, Tahun, dan Custom | +500
        // 3. Total Orderan: Hari, Bulan, Tahun, dan Custom
        // 4. Total Top Up: Hari, Bulan, Tahun, dan Custom

        // Laporan Tiap Driver:
        // 1. Total Order: Hari, Bulan, Tahun, dan Custom
        // 2. Total Pemasukan Bersih Ongkir Food: Hari, Bulan, Tahun, dan Custom | ongkir - 500
        // 3. Total Pemasukan Bersih Ongkos Ride: Hari, Bulan, Tahun, dan Custom | -500
        // 4. Total Top Up: Hari, Bulan, Tahun, dan Custom

        $data = [
            'title' => 'Laporan',
        ];

        return view('pages/laporan', $data);
    }
}
