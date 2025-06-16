<?php
session_start();
include_once __DIR__ . '/../config/koneksi.php';

// Cek apakah pengguna sudah login
if (!isset($_SESSION['id_user'])) {
    header('Location: ' . '/aca_pos/index.php?pesan=belum_login');
    exit;
}
// Ambil nama user untuk ditampilkan
$nama_user = $_SESSION['nama_user'] ?? 'Pengguna'; 
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Ganti title secara dinamis -->
    <title><?php echo isset($title) ? $title . ' - ACA POS' : 'ACA POS'; ?></title>
    
    <!-- Link Font dari Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Stylesheets -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Pastikan path ke style.css sudah benar -->
    <link rel="stylesheet" href="/aca_pos/assets/css/style.css">
</head>
<body>
    <div class="d-flex">