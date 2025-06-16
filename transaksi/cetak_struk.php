<?php
include '../config/koneksi.php';

if (!isset($_GET['id'])) {
    die("ID Transaksi tidak ditemukan.");
}

$id_transaksi = (int)$_GET['id'];
$query_transaksi = "SELECT t.*, u.nama_lengkap AS nama_kasir 
                    FROM transaksi t 
                    JOIN users u ON t.id_user = u.id_user 
                    WHERE id_transaksi = $id_transaksi";
$transaksi = mysqli_fetch_assoc(mysqli_query($koneksi, $query_transaksi));

$query_detail = "SELECT dt.*, i.nama_item FROM detail_transaksi dt JOIN item i ON dt.id_item = i.id_item WHERE dt.id_transaksi = $id_transaksi";
$detail_result = mysqli_query($koneksi, $query_detail);

// Lebar struk dalam karakter, misal 48 untuk printer 80mm atau 32 untuk 58mm
$width = 32;

// --- PERBAIKAN DI SINI ---
// Nama fungsi harus dipisahkan dengan spasi, bukan tanda hubung
function cetak_garis($width) {
    return str_repeat('-', $width) . "\n";
}

function buat_baris($kiri, $kanan, $width) {
    $panjang_kiri = strlen($kiri);
    $panjang_kanan = strlen($kanan);
    // Tambah validasi untuk mencegah error jika string terlalu panjang
    if ($panjang_kiri + $panjang_kanan > $width) {
        $spasi = 1; // Beri spasi minimal
    } else {
        $spasi = $width - $panjang_kiri - $panjang_kanan;
    }
    return $kiri . str_repeat(' ', $spasi) . $kanan . "\n";
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Struk Pembayaran</title>
    <style>
        body {
            font-family: 'Courier New', monospace;
            font-size: 10pt;
            width: <?= $width ?>ch; /* Sesuaikan dengan lebar printer */
            margin: 0;
            padding: 5px;
        }
        .header {
            text-align: center;
        }
        .text-right {
            text-align: right;
        }
        pre {
             white-space: pre-wrap;
             word-wrap: break-word;
             margin: 0;
        }
    </style>
</head>
<body onload="window.print()">
    <pre>
<div class="header">
<strong>ACA POS</strong>
Jl. Aplikasi No. 123
Telp: 081234567890
</div>
<?= cetak_garis($width); ?>
<?= buat_baris($transaksi['kode_invoice'], date('d/m/y H:i', strtotime($transaksi['tanggal_transaksi'])), $width); ?>
Kasir: <?= $transaksi['nama_kasir'] ."\n"; ?>
<?= cetak_garis($width); ?>
<?php
while($item = mysqli_fetch_assoc($detail_result)) {
    echo $item['nama_item'] . "\n";
    $harga = number_format($item['harga_saat_transaksi']);
    $total_item = number_format($item['sub_total']);
    // -1 agar ada margin di kanan
    echo buat_baris(" " . $item['jumlah'] . "x " . $harga, $total_item, $width-1); 
}
?>
<?= cetak_garis($width); ?>
<?= buat_baris("Subtotal", number_format($transaksi['total_belanja']), $width); ?>
<?php
$pajak = $transaksi['total_final'] - $transaksi['total_belanja'];
if ($pajak > 0) {
    echo buat_baris("Pajak(".$transaksi['pajak_persen']."%)", number_format($pajak), $width);
}
?>
<?= cetak_garis($width); ?>
<?= buat_baris("TOTAL", number_format($transaksi['total_final']), $width); ?>
<?= cetak_garis($width); ?>
<?= buat_baris("Tunai", number_format($transaksi['tunai']), $width); ?>
<?= buat_baris("Kembali", number_format($transaksi['kembalian']), $width); ?>
<?= cetak_garis($width); ?>
Metode: <?= $transaksi['metode_pembayaran'] ."\n"; ?>

<div class="header">
Terima Kasih
Telah Berbelanja
</div>
    </pre>
</body>
</html>