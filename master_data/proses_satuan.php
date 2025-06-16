<?php
session_start();
include '../config/koneksi.php';

// Proses TAMBAH data
if (isset($_POST['tambah'])) {
    // Hanya admin yang boleh menambah
    if ($_SESSION['role'] != 'administrator') {
        header('Location: satuan.php?error=Anda tidak punya akses untuk aksi ini');
        exit;
    }

    $nama_satuan = mysqli_real_escape_string($koneksi, $_POST['nama_satuan']);

    if (empty($nama_satuan)) {
         header('Location: satuan.php?error=Nama satuan tidak boleh kosong');
        exit;
    }
    
    $query = "INSERT INTO satuan (nama_satuan) VALUES ('$nama_satuan')";

    if (mysqli_query($koneksi, $query)) {
        header('Location: satuan.php?sukses=Satuan berhasil ditambahkan');
    } else {
        header('Location: satuan.php?error=' . urlencode(mysqli_error($koneksi)));
    }
    exit;
}

// Proses HAPUS data
if (isset($_GET['hapus'])) {
    // Hanya admin yang boleh menghapus
    if ($_SESSION['role'] != 'administrator') {
        header('Location: satuan.php?error=Anda tidak punya akses untuk aksi ini');
        exit;
    }

    $id_satuan = (int)$_GET['hapus'];
    $query = "DELETE FROM satuan WHERE id_satuan = $id_satuan";

    if (mysqli_query($koneksi, $query)) {
        header('Location: satuan.php?sukses=Satuan berhasil dihapus');
    } else {
        // Error mungkin terjadi jika satuan masih dipakai oleh item (foreign key constraint)
        header('Location: satuan.php?error=Gagal menghapus. Satuan mungkin masih digunakan oleh item.');
    }
    exit;
}
?>