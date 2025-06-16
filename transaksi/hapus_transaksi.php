<?php
session_start();
include '../config/koneksi.php';

// Langkah 1: Proteksi Halaman, hanya Administrator yang bisa mengakses.
// Jika role bukan administrator, hentikan eksekusi script.
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'administrator') {
    die("AKSES DITOLAK: Anda tidak memiliki izin untuk melakukan aksi ini.");
}

if (isset($_GET['id'])) {

    $id_transaksi = (int)$_GET['id'];

    $query = "DELETE FROM transaksi WHERE id_transaksi = ?";
    $stmt = mysqli_prepare($koneksi, $query);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $id_transaksi);
        
        if (mysqli_stmt_execute($stmt)) {
            header('Location: daftar_transaksi.php?sukses=Data transaksi berhasil dihapus.');
        } else {
            header('Location: daftar_transaksi.php?error=Gagal menghapus transaksi: ' . urlencode(mysqli_stmt_error($stmt)));
        }
        
        mysqli_stmt_close($stmt);
    } else {
        header('Location: daftar_transaksi.php?error=Gagal mempersiapkan query: ' . urlencode(mysqli_error($koneksi)));
    }

    exit;

} else {
    header('Location: daftar_transaksi.php');
    exit;
}
?>