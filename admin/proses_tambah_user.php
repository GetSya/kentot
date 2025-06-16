<?php
session_start();
include '../config/koneksi.php';

// Proteksi: Hanya administrator yang boleh mengakses proses ini
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'administrator') {
    die("Akses ditolak. Anda bukan administrator.");
}

// Cek apakah form telah disubmit
if (isset($_POST['tambah_user'])) {
    
    // Ambil data dari form dan lakukan sanitasi dasar
    $nama_lengkap = mysqli_real_escape_string($koneksi, $_POST['nama_lengkap']);
    $username     = mysqli_real_escape_string($koneksi, $_POST['username']);
    $password     = mysqli_real_escape_string($koneksi, $_POST['password']);
    $role         = mysqli_real_escape_string($koneksi, $_POST['role']);

    // Validasi dasar
    if (empty($nama_lengkap) || empty($username) || empty($password) || empty($role)) {
        header('Location: users.php?error=Semua field wajib diisi');
        exit;
    }

    // PENTING: Hash password sebelum disimpan ke database
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Cek apakah username sudah ada
    $check_user = mysqli_query($koneksi, "SELECT username FROM users WHERE username = '$username'");
    if (mysqli_num_rows($check_user) > 0) {
        header('Location: users.php?error=Username sudah terpakai, silakan gunakan username lain.');
        exit;
    }

    // Query untuk insert data
    $query = "INSERT INTO users (nama_lengkap, username, password, role) VALUES ('$nama_lengkap', '$username', '$hashed_password', '$role')";

    if (mysqli_query($koneksi, $query)) {
        // Jika berhasil, redirect kembali ke halaman users dengan pesan sukses
        header('Location: users.php?sukses=User baru berhasil ditambahkan.');
    } else {
        // Jika gagal, redirect dengan pesan error
        $error_message = urlencode(mysqli_error($koneksi));
        header('Location: users.php?error=Gagal menambahkan user: ' . $error_message);
    }
    exit;

} else {
    // Jika diakses langsung tanpa submit form, tendang ke halaman user
    header('Location: users.php');
    exit;
}
?>