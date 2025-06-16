<?php
session_start();
include '../config/koneksi.php';

// Cek hak akses admin
function check_admin() {
    if (!isset($_SESSION['role']) || $_SESSION['role'] != 'administrator') {
        header('Location: item.php?error=Anda tidak punya akses untuk aksi ini');
        exit;
    }
}

// Proses TAMBAH item
if (isset($_POST['tambah'])) {
    check_admin();

    $kode_item = mysqli_real_escape_string($koneksi, $_POST['kode_item']);
    $nama_item = mysqli_real_escape_string($koneksi, $_POST['nama_item']);
    $id_satuan = (int)$_POST['id_satuan'];
    $harga_jual = (float)$_POST['harga_jual'];
    $stok_minimum = (int)$_POST['stok_minimum'];

    // Validasi input tidak kosong
    if (empty($kode_item) || empty($nama_item) || empty($id_satuan) || empty($harga_jual)) {
         header('Location: item.php?error=Semua field wajib diisi');
         exit;
    }

    // Cek duplikasi kode item
    $check_kode = mysqli_query($koneksi, "SELECT kode_item FROM item WHERE kode_item = '$kode_item'");
    if (mysqli_num_rows($check_kode) > 0) {
        header('Location: item.php?error=Kode Item sudah ada, gunakan kode lain.');
        exit;
    }

    // Stok awal saat item baru dibuat adalah 0. Stok ditambah melalui menu 'Tambah Stok'
    $query = "INSERT INTO item (kode_item, nama_item, id_satuan, harga_jual, stok, stok_minimum) 
              VALUES ('$kode_item', '$nama_item', $id_satuan, $harga_jual, 0, $stok_minimum)";
    
    if (mysqli_query($koneksi, $query)) {
        header('Location: item.php?sukses=Item berhasil ditambahkan');
    } else {
        header('Location: item.php?error=' . urlencode(mysqli_error($koneksi)));
    }
    exit;
}

// Proses HAPUS item
if (isset($_GET['hapus'])) {
    check_admin();
    
    $id_item = (int)$_GET['hapus'];
    $query = "DELETE FROM item WHERE id_item = $id_item";

    if (mysqli_query($koneksi, $query)) {
        header('Location: item.php?sukses=Item berhasil dihapus');
    } else {
        header('Location: item.php?error=Gagal menghapus item. Mungkin item sudah ada di transaksi.');
    }
    exit;
}
?>