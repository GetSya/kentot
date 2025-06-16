<?php
session_start();
include '../config/koneksi.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'administrator') {
    die("Akses ditolak. Anda bukan administrator.");
}

if (isset($_GET['id'])) {
    $id_user_to_delete = (int)$_GET['id']; // Cast ke integer untuk keamanan
    $id_user_logged_in = (int)$_SESSION['id_user'];

    if ($id_user_to_delete == $id_user_logged_in) {
        header('Location: users.php?error=Anda tidak dapat menghapus akun Anda sendiri.');
        exit;
    }

    $query = "DELETE FROM users WHERE id_user = $id_user_to_delete";

    if (mysqli_query($koneksi, $query)) {
        header('Location: users.php?sukses=User berhasil dihapus.');
    } else {
        $error_message = urlencode(mysqli_error($koneksi));
        header('Location: users.php?error=Gagal menghapus user: ' . $error_message);
    }
    exit;

} else {
    header('Location: users.php');
    exit;
}
?>