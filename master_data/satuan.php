<?php
$title = "Daftar Satuan";
include __DIR__ . '/../template/header.php';
include __DIR__ . '/../template/sidebar.php';
?>

<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Nama Satuan</h1>
        <?php if ($_SESSION['role'] == 'administrator') : ?>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tambahSatuanModal">
            <i class="bi bi-plus-circle"></i> Tambah Satuan
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
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Satuan</th>
                            <?php if ($_SESSION['role'] == 'administrator') : ?>
                            <th>Aksi</th>
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $query = mysqli_query($koneksi, "SELECT * FROM satuan ORDER BY nama_satuan ASC");
                        $no = 1;
                        while ($data = mysqli_fetch_assoc($query)) {
                        ?>
                            <tr>
                                <td><?= $no++; ?></td>
                                <td><?= htmlspecialchars($data['nama_satuan']); ?></td>
                                <?php if ($_SESSION['role'] == 'administrator') : ?>
                                <td>
                                    <a href="proses_satuan.php?hapus=<?= $data['id_satuan']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus satuan ini?')">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                </td>
                                <?php endif; ?>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah Satuan (Hanya bisa diakses admin) -->
<?php if ($_SESSION['role'] == 'administrator') : ?>
<div class="modal fade" id="tambahSatuanModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Tambah Satuan Baru</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="proses_satuan.php" method="POST">
        <div class="modal-body">
            <div class="mb-3">
                <label for="nama_satuan" class="form-label">Nama Satuan</label>
                <input type="text" class="form-control" id="nama_satuan" name="nama_satuan" placeholder="Contoh: Pcs, Box, Kg" required>
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