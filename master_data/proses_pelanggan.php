<?php
include '../config/koneksi.php';

if (isset($_POST['tambah'])) {
    $nama_pelanggan = mysqli_real_escape_string($koneksi, $_POST['nama_pelanggan']);
    $alamat = mysqli_real_escape_string($koneksi, $_POST['alamat']);
    $no_hp = mysqli_real_escape_string($koneksi, $_POST['no_hp']);

    $query = "INSERT INTO pelanggan (nama_pelanggan, alamat, no_hp) VALUES ('$nama_pelanggan', '$alamat', '$no_hp')";

    if (mysqli_query($koneksi, $query)) {
        header('Location: pelanggan.php?sukses=Data pelanggan berhasil ditambahkan');
    } else {
        // Handle error, misalnya kembali ke halaman sebelumnya dengan pesan error
        header('Location: pelanggan.php?error=' . urlencode(mysqli_error($koneksi)));
    }
}
?>