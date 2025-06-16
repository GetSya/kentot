<?php
$title = "Daftar Transaksi";
include __DIR__ . '/../template/header.php';
include __DIR__ . '/../template/sidebar.php';
?>

<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Daftar Transaksi</h1>

    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Invoice</th>
                            <th>Tanggal</th>
                            <th>Pelanggan</th>
                            <th>Kasir</th>
                            <th>Total</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                        $query = "SELECT t.*, p.nama_pelanggan, u.nama_lengkap AS nama_kasir
                                  FROM transaksi t
                                  LEFT JOIN pelanggan p ON t.id_pelanggan = p.id_pelanggan
                                  JOIN users u ON t.id_user = u.id_user
                                  ORDER BY t.tanggal_transaksi DESC";
                        $result = mysqli_query($koneksi, $query);
                        $no = 1;
                        while($data = mysqli_fetch_assoc($result)) {
                    ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td><?= $data['kode_invoice']; ?></td>
                            <td><?= date('d M Y, H:i', strtotime($data['tanggal_transaksi'])); ?></td>
                            <td><?= $data['nama_pelanggan'] ?: 'Umum'; ?></td>
                            <td><?= $data['nama_kasir']; ?></td>
                            <td><?= format_rupiah($data['total_final']); ?></td>
                            <td>
                                <a href="detail_transaksi.php?id=<?= $data['id_transaksi']; ?>" class="btn btn-info btn-sm"><i class="bi bi-eye"></i> Detail</a>
                                <?php if ($_SESSION['role'] == 'administrator'): ?>
                                    <a href="hapus_transaksi.php?id=<?= $data['id_transaksi']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus transaksi ini? Stok tidak akan dikembalikan!')"><i class="bi bi-trash"></i> Hapus</a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php
include __DIR__ . '/../template/footer.php';
?>