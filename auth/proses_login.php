<?php
session_start();
include '../config/koneksi.php';

$username = mysqli_real_escape_string($koneksi, $_POST['username']);
$password = mysqli_real_escape_string($koneksi, $_POST['password']);

if (empty($username) || empty($password)) {
    header('Location: ../index.php?pesan=gagal');
    exit;
}

$query = "SELECT * FROM users WHERE username = '$username'";
$result = mysqli_query($koneksi, $query);
$user = mysqli_fetch_assoc($result);

if ($user && password_verify($password, $user['password'])) {
    // Login berhasil
    $_SESSION['id_user'] = $user['id_user'];
    $_SESSION['username'] = $user['username'];
    $_SESSION['nama_lengkap'] = $user['nama_lengkap'];
    $_SESSION['role'] = $user['role'];

    header('Location: ../dashboard.php');
} else {
    // Login gagal
    header('Location: ../index.php?pesan=gagal');
}
?>