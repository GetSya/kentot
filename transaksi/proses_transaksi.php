<?php
session_start();
include '../config/koneksi.php';

if (!isset($_POST['simpan_transaksi'])) {
    // Redirect jika akses langsung
    header('Location: transaksi_baru.php');
    exit;
}

// Mengambil data dari form
$id_pelanggan = $_POST['id_pelanggan'] ? (int)$_POST['id_pelanggan'] : null;
$id_user = (int)$_SESSION['id_user'];
$pajak_persen = (float)$_POST['pajak_persen'];
$total_final = (float)$_POST['total_final'];
$tunai = (float)preg_replace('/[^0-9]/', '', $_POST['tunai']);
$metode_pembayaran = mysqli_real_escape_string($koneksi, $_POST['metode_pembayaran']);
$keranjang = json_decode($_POST['keranjang'], true);

if (empty($keranjang)) {
    die("Error: Keranjang kosong!");
}

// Mulai transaksi database
mysqli_begin_transaction($koneksi);

try {
    // 1. Hitung total belanja murni dari keranjang (server-side)
    $total_belanja_server = 0;
    foreach ($keranjang as $item) {
        $total_belanja_server += $item['harga'] * $item['jumlah'];
    }
    
    // 2. Hitung total final server-side
    $pajak_nominal = $total_belanja_server * ($pajak_persen / 100);
    $total_final_server = $total_belanja_server + $pajak_nominal;

    // 3. Hitung kembalian
    $kembalian = $tunai - $total_final_server;
    
    // 4. Generate Kode Invoice
    $kode_invoice = 'INV-' . date('Ymd') . '-' . mt_rand(1000, 9999);

    // 5. Insert ke tabel 'transaksi'
    $stmt_transaksi = mysqli_prepare($koneksi, "INSERT INTO transaksi (kode_invoice, id_pelanggan, id_user, tanggal_transaksi, total_belanja, pajak_persen, total_final, tunai, kembalian, metode_pembayaran) VALUES (?, ?, ?, NOW(), ?, ?, ?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt_transaksi, "siisdddds", 
        $kode_invoice, $id_pelanggan, $id_user, $total_belanja_server, $pajak_persen, $total_final_server, $tunai, $kembalian, $metode_pembayaran);
    mysqli_stmt_execute($stmt_transaksi);
    $id_transaksi_baru = mysqli_insert_id($koneksi);

    // 6. Insert ke tabel 'detail_transaksi' dan update stok
    $stmt_detail = mysqli_prepare($koneksi, "INSERT INTO detail_transaksi (id_transaksi, id_item, jumlah, harga_saat_transaksi, sub_total) VALUES (?, ?, ?, ?, ?)");
    $stmt_update_stok = mysqli_prepare($koneksi, "UPDATE item SET stok = stok - ? WHERE id_item = ?");
    
    foreach ($keranjang as $item) {
        $id_item = (int)$item['id'];
        $jumlah = (int)$item['jumlah'];
        $harga = (float)$item['harga'];
        $sub_total = $harga * $jumlah;

        // Insert detail
        mysqli_stmt_bind_param($stmt_detail, "iiidd", $id_transaksi_baru, $id_item, $jumlah, $harga, $sub_total);
        mysqli_stmt_execute($stmt_detail);
        
        // Update stok
        mysqli_stmt_bind_param($stmt_update_stok, "ii", $jumlah, $id_item);
        mysqli_stmt_execute($stmt_update_stok);
    }
    
    // Jika semua query berhasil, commit transaksi
    mysqli_commit($koneksi);

    // Redirect ke halaman cetak struk
    header('Location: cetak_struk.php?id=' . $id_transaksi_baru);
    exit;

} catch (mysqli_sql_exception $exception) {
    // Jika ada error, rollback transaksi
    mysqli_rollback($koneksi);
    
    // Tampilkan error
    // Sebaiknya di-log ke file di production environment
    die("Transaksi Gagal: " . $exception->getMessage());
}

?>