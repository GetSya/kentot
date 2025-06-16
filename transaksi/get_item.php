<?php
include '../config/koneksi.php';
header('Content-Type: application/json');

$term = isset($_GET['term']) ? mysqli_real_escape_string($koneksi, $_GET['term']) : '';

$query = "SELECT id_item, kode_item, nama_item, harga_jual, stok FROM item WHERE (nama_item LIKE '%$term%' OR kode_item LIKE '%$term%') AND stok > 0 LIMIT 10";
$result = mysqli_query($koneksi, $query);

$items = [];
while ($row = mysqli_fetch_assoc($result)) {
    $items[] = [
        'id' => $row['id_item'],
        'label' => $row['kode_item'] . ' - ' . $row['nama_item'],
        'value' => $row['nama_item'],
        'harga' => $row['harga_jual'],
        'stok' => $row['stok']
    ];
}

echo json_encode($items);
?>