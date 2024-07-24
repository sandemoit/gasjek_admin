<?php

use App\Models\ApplicationModel;

if (!function_exists('application')) {
    /**
     * Get application settings from tb_application table.
     *
     * @param string $column_name
     * @return mixed
     */
    function application($column_name)
    {
        $applicationModel = new ApplicationModel();

        // Ambil data berdasarkan nama kolom dari tabel tb_application
        $setting = $applicationModel->select($column_name)->first();

        // Jika data tidak ditemukan, kembalikan null
        if (!$setting) {
            return null;
        }

        // Kembalikan nilai kolom yang diminta
        return isset($setting[$column_name]) ? $setting[$column_name] : '';
    }

    if (!function_exists('rupiah')) {
        function rupiah($number)
        {
            return 'Rp ' . number_format($number, 0, ',', '.');
        }
    }
}

if (!function_exists('rupiahMidtrans')) {
    function rupiahMidtrans($number)
    {
        return 'Rp. ' . number_format($number, 2, ',', '.');
    }
}

if (!function_exists('tanggal')) {
    function tanggal($tanggal)
    {
        $ubahTanggal = date('Y-m-d H:i:s', strtotime($tanggal) + 60 * 60 * 8);
        $pecahTanggal = explode('-', $ubahTanggal);
        $tanggal = (int)$pecahTanggal[2];
        $bulan = (int)$pecahTanggal[1];
        $tahun = (int)$pecahTanggal[0];
        $namaHari = nama_hari(date('l', mktime(0, 0, 0, $bulan, $tanggal, $tahun)));
        return $namaHari . ', ' . $tanggal . ' ' . bulan_panjang($bulan) . ' ' . $tahun;
    }
}

if (!function_exists('bulan_panjang')) {
    function bulan_panjang($bulan)
    {
        switch ($bulan) {
            case 1:
                return 'Januari';
                break;
            case 2:
                return 'Februari';
                break;
            case 3:
                return 'Maret';
                break;
            case 4:
                return 'April';
                break;
            case 5:
                return 'Mei';
                break;
            case 6:
                return 'Juni';
                break;
            case 7:
                return 'Juli';
                break;
            case 8:
                return 'Agustus';
                break;
            case 9:
                return 'September';
                break;
            case 10:
                return 'Oktober';
                break;
            case 11:
                return 'November';
                break;
            case 12:
                return 'Desember';
                break;
        }
    }
}
if (!function_exists('nama_hari')) {
    function nama_hari($hari)
    {
        if ($hari == 'Sunday') {
            return 'Minggu';
        } elseif ($hari == 'Monday') {
            return 'Senin';
        } elseif ($hari == 'Tuesday') {
            return 'Selasa';
        } elseif ($hari == 'Wednesday') {
            return 'Rabu';
        } elseif ($hari == 'Thursday') {
            return 'Kamis';
        } elseif ($hari == 'Friday') {
            return 'Jumat';
        } elseif ($hari == 'Saturday') {
            return 'Sabtu';
        }
    }
}

// buatkan format jam
if (!function_exists('jam')) {
    function jam($hour)
    {
        // Ensure the hour is within valid range
        if ($hour < 0 || $hour > 23) {
            return 'Invalid hour';
        }

        // Format the hour with leading zero if necessary
        return sprintf('%02d:00', $hour);
    }
}
if (!function_exists('write_log')) {
    function write_log($level, $message, $function)
    {
        $logDatas = "Level: " . $level .
            ", Message: " . $message .
            ", Function: " . $function .
            ", Time: " . date('Y-m-d H:i:s');
        $logFile = WRITEPATH . 'logs/log - ' . date('Y-m-d H:i:s') . '.txt';
        file_put_contents($logFile, json_encode($logDatas) . "\n", FILE_APPEND);
    }
}
