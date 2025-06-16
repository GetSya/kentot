<?php
$title = "Detail Transaksi";
include __DIR__ . '/../template/header.php';
include __DIR__ . '/../template/sidebar.php';

$id_transaksi = (int)$_GET['id'];
$query_transaksi = "SELECT t.*, p.nama_pelanggan, u.nama_lengkap AS nama_kasir
                    FROM transaksi t
                    LEFT JOIN pelanggan p ON t.id_pelanggan = p.id_pelanggan
                    JOIN users u ON t.id_user = u.id_user
                    WHERE t.id_transaksi = $id_transaksi";
$transaksi = mysqli_fetch_assoc(mysqli_query($koneksi, $query_transaksi));

$query_detail = "SELECT dt.*, i.nama_item, i.kode_item 
                 FROM detail_transaksi dt
                 JOIN item i ON dt.id_item = i.id_item
                 WHERE dt.id_transaksi = $id_transaksi";
$detail_result = mysqli_query($koneksi, $query_detail);
?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Detail Transaksi: <?= $transaksi['kode_invoice']; ?></h1>
        <a href="cetak_struk.php?id=<?= $id_transaksi ?>" target="_blank" class="btn btn-primary"><i class="bi bi-printer"></i> Cetak Struk</a>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p><strong>Tanggal:</strong> <?= date('d M Y H:i:s', strtotime($transaksi['tanggal_transaksi'])) ?></p>
                            <p><strong>Pelanggan:</strong> <?= $transaksi['nama_pelanggan'] ?: 'Pelanggan Umum' ?></p>
                            <p><strong>Kasir:</strong> <?= $transaksi['nama_kasir'] ?></p>
                        </div>
                        <div class="col-md-6 text-md-end">
                            <p><strong>Metode Pembayaran:</strong> <span class="badge bg-info"><?= $transaksi['metode_pembayaran'] ?></span></p>
                            <p><strong>Pajak:</strong> <?= $transaksi['pajak_persen'] ?>%</p>
                        </div>
                    </div>
                    <hr>
                    <h5>Item yang Dibeli:</h5>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Kode</th>
                                <th>Nama Item</th>
                                <th class="text-end">Harga Satuan</th>
                                <th class="text-center">Jumlah</th>
                                <th class="text-end">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php while ($item = mysqli_fetch_assoc($detail_result)): ?>
                            <tr>
                                <td><?= $item['kode_item'] ?></td>
                                <td><?= $item['nama_item'] ?></td>
                                <td class="text-end"><?= format_rupiah($item['harga_saat_transaksi']) ?></td>
                                <td class="text-center"><?= $item['jumlah'] ?></td>
                                <td class="text-end"><?= format_rupiah($item['sub_total']) ?></td>
                            </tr>
                        <?php endwhile; ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="4" class="text-end">Total Belanja</th>
                                <th class="text-end"><?= format_rupiah($transaksi['total_belanja']) ?></th>
                            </tr>
                             <tr>
                                <th colspan="4" class="text-end">Pajak (<?= $transaksi['pajak_persen'] ?>%)</th>
                                <th class="text-end"><?= format_rupiah($transaksi['total_final'] - $transaksi['total_belanja']) ?></th>
                            </tr>
                            <tr>
                                <th colspan="4" class="text-end fw-bold">Total Final</th>
                                <th class="text-end fw-bold"><?= format_rupiah($transaksi['total_final']) ?></th>
                            </tr>
                            <tr>
                                <th colspan="4" class="text-end">Tunai</th>
                                <th class="text-end"><?= format_rupiah($transaksi['tunai']) ?></th>
                            </tr>
                             <tr>
                                <th colspan="4" class="text-end">Kembalian</th>
                                <th class="text-end"><?= format_rupiah($transaksi['kembalian']) ?></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
include __DIR__ . '/../template/footer.php';
?>