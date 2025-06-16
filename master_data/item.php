<?php
$title = "Daftar Item";
include __DIR__ . '/../template/header.php';
include __DIR__ . '/../template/sidebar.php';

// BARU: Ambil kata kunci pencarian dari URL (jika ada)
$keyword = '';
if (isset($_GET['keyword'])) {
    $keyword = mysqli_real_escape_string($koneksi, trim($_GET['keyword']));
}
?>

<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Daftar Item</h1>
        <?php if ($_SESSION['role'] == 'administrator') : ?>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tambahItemModal">
            <i class="bi bi-plus-circle"></i> Tambah Item
        </button>
        <?php endif; ?>
    </div>
    
    <!-- Pesan Sukses/Error -->
    <?php if (isset($_GET['sukses'])) : ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?= htmlspecialchars($_GET['sukses']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
     <?php elseif (isset($_GET['error'])) : ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?= htmlspecialchars($_GET['error']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php endif; ?>

    <div class="card shadow mb-4">
        <div class="card-body">
            
            <!-- BARU: Form Pencarian -->
            <div class="row mb-3">
                <div class="col-md-6">
                    <form action="" method="GET">
                        <div class="input-group">
                            <input type="text" class="form-control" name="keyword" placeholder="Cari berdasarkan Kode atau Nama Item..." value="<?= htmlspecialchars($keyword); ?>">
                            <button class="btn btn-primary" type="submit"><i class="bi bi-search"></i> Cari</button>
                            <?php if (!empty($keyword)): // Tampilkan tombol reset hanya jika ada pencarian ?>
                                <a href="item.php" class="btn btn-secondary"><i class="bi bi-arrow-counterclockwise"></i> Reset</a>
                            <?php endif; ?>
                        </div>
                    </form>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode Item</th>
                            <th>Nama Item</th>
                            <th>Satuan</th>
                            <th>Harga Jual</th>
                            <th>Stok</th>
                            <?php if ($_SESSION['role'] == 'administrator') : ?>
                            <th>Aksi</th>
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // DIUBAH: Query dimodifikasi untuk memasukkan kondisi pencarian
                        $query = "SELECT item.*, satuan.nama_satuan FROM item 
                                  JOIN satuan ON item.id_satuan = satuan.id_satuan";

                        // Jika ada kata kunci pencarian, tambahkan klausa WHERE
                        if (!empty($keyword)) {
                            // Mencari di kolom kode_item ATAU nama_item
                            $query .= " WHERE (item.kode_item LIKE '%$keyword%' OR item.nama_item LIKE '%$keyword%')";
                        }
                        
                        $query .= " ORDER BY item.nama_item ASC";

                        $result = mysqli_query($koneksi, $query);

                        // BARU: Cek jika tidak ada hasil
                        if (mysqli_num_rows($result) == 0 && !empty($keyword)) {
                            echo '<tr><td colspan="7" class="text-center">Data tidak ditemukan untuk kata kunci: "' . htmlspecialchars($keyword) . '"</td></tr>';
                        } else {
                            $no = 1;
                            while ($data = mysqli_fetch_assoc($result)) {
                            ?>
                                <tr>
                                    <td><?= $no++; ?></td>
                                    <td><?= htmlspecialchars($data['kode_item']); ?></td>
                                    <td><?= htmlspecialchars($data['nama_item']); ?></td>
                                    <td><?= htmlspecialchars($data['nama_satuan']); ?></td>
                                    <td><?= format_rupiah($data['harga_jual']); ?></td>
                                    <td><?= $data['stok']; ?></td>
                                    <?php if ($_SESSION['role'] == 'administrator') : ?>
                                    <td>
                                        <!-- Nanti bisa ditambah tombol edit disini -->
                                        <a href="proses_item.php?hapus=<?= $data['id_item']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus item ini?')">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                    </td>
                                    <?php endif; ?>
                                </tr>
                            <?php } 
                        } // akhir dari else
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah Item (Kode tidak berubah, tetap sama) -->
<?php if ($_SESSION['role'] == 'administrator') : ?>
<div class="modal fade" id="tambahItemModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Tambah Item Baru</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="proses_item.php" method="POST">
        <div class="modal-body">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="kode_item" class="form-label">Kode Item</label>
                    <input type="text" class="form-control" id="kode_item" name="kode_item" required>
                </div>
                 <div class="col-md-6 mb-3">
                    <label for="nama_item" class="form-label">Nama Item</label>
                    <input type="text" class="form-control" id="nama_item" name="nama_item" required>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="id_satuan" class="form-label">Satuan</label>
                    <select class="form-select" name="id_satuan" id="id_satuan" required>
                        <option value="" disabled selected>-- Pilih Satuan --</option>
                        <?php
                            $satuan_query = mysqli_query($koneksi, "SELECT * FROM satuan");
                            while($s = mysqli_fetch_assoc($satuan_query)){
                                echo "<option value='{$s['id_satuan']}'>{$s['nama_satuan']}</option>";
                            }
                        ?>
                    </select>
                </div>
                 <div class="col-md-6 mb-3">
                    <label for="harga_jual" class="form-label">Harga Jual</label>
                    <input type="number" class="form-control" id="harga_jual" name="harga_jual" min="0" required>
                </div>
            </div>
             <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="stok_minimum" class="form-label">Stok Minimum (Alert)</label>
                    <input type="number" class="form-control" id="stok_minimum" name="stok_minimum" value="10" min="0" required>
                    <small class="form-text text-muted">Akan muncul di laporan jika stok kurang dari atau sama dengan nilai ini.</small>
                </div>
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="submit" name="tambah" class="btn btn-primary">Simpan</button>
        </div>
      </form>
    </div>
  </div>
</div>
<?php endif; ?>


<?php
include __DIR__ . '/../template/footer.php';
?>