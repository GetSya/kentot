<?php
// Pengaturan zona waktu
date_default_timezone_set('Asia/Jakarta');

// Koneksi ke database
$host = 'localhost';
$user = 'root'; // Sesuaikan dengan username db Anda
$pass = ''; // Sesuaikan dengan password db Anda
$db   = 'aca_pos';

$koneksi = mysqli_connect($host, $user, $pass, $db);

if (!$koneksi) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// Fungsi untuk format rupiah
function format_rupiah($angka){
    return "Rp " . number_format($angka, 0, ',', '.');
}
?>