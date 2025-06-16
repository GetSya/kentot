<?php
$title = "Tambah Stok Item";
include __DIR__ . '/../template/header.php';
include __DIR__ . '/../template/sidebar.php';
?>

<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Tambah Stok Item</h1>
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
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Formulir Penambahan Stok</h6>
        </div>
        <div class="card-body">
            <form action="proses_stok.php" method="POST">
                <div class="mb-3">
                    <label for="id_item" class="form-label">Pilih Item</label>
                    <select class="form-select" name="id_item" id="id_item" required>
                        <option value="" disabled selected>-- Cari dan Pilih Item --</option>
                        <?php
                            $item_query = mysqli_query($koneksi, "SELECT id_item, kode_item, nama_item FROM item ORDER BY nama_item ASC");
                            while($i = mysqli_fetch_assoc($item_query)){
                                echo "<option value='{$i['id_item']}'>{$i['kode_item']} - {$i['nama_item']}</option>";
                            }
                        ?>
                    </select>
                </div>
                 <div class="mb-3">
                    <label for="jumlah_tambah" class="form-label">Jumlah Stok yang Ditambah</label>
                    <input type="number" class="form-control" id="jumlah_tambah" name="jumlah_tambah" min="1" required>
                </div>
                <button type="submit" name="tambah_stok" class="btn btn-primary">
                    <i class="bi bi-plus-square"></i> Tambah Stok
                </button>
            </form>
        </div>
    </div>

</div>


<?php
// Untuk fungsionalitas pencarian di dropdown, Anda bisa menggunakan library seperti Select2.js
// Tapi untuk PHP Native murni, dropdown biasa sudah cukup.
include __DIR__ . '/../template/footer.php';
?>