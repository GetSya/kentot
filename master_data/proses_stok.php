<?php
session_start();
include '../config/koneksi.php';

// Proses ini bisa diakses Admin dan Kasir
if (!isset($_SESSION['id_user'])) {
    header('Location: /aca_pos/index.php?pesan=belum_login');
    exit;
}


if (isset($_POST['tambah_stok'])) {
    $id_item = (int)$_POST['id_item'];
    $jumlah_tambah = (int)$_POST['jumlah_tambah'];

    // Validasi
    if (empty($id_item) || empty($jumlah_tambah) || $jumlah_tambah <= 0) {
        header('Location: stok.php?error=Harap pilih item dan isi jumlah dengan benar.');
        exit;
    }

    // Query untuk UPDATE, bukan INSERT.
    // Menambahkan nilai stok yang ada dengan jumlah baru.
    $query = "UPDATE item SET stok = stok + ? WHERE id_item = ?";
    $stmt = mysqli_prepare($koneksi, $query);
    
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "ii", $jumlah_tambah, $id_item);
        if (mysqli_stmt_execute($stmt)) {
            // Jika berhasil, arahkan ke halaman daftar item agar bisa langsung terlihat perubahannya
            header('Location: item.php?sukses=Stok untuk item berhasil diperbarui.');
        } else {
            header('Location: stok.php?error=Gagal memperbarui stok: ' . urlencode(mysqli_stmt_error($stmt)));
        }
        mysqli_stmt_close($stmt);
    } else {
         header('Location: stok.php?error=Query gagal dipersiapkan: ' . urlencode(mysqli_error($koneksi)));
    }
    exit;

} else {
    header('Location: stok.php');
    exit;
}
?>