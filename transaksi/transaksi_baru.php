<?php
$title = "Transaksi Baru";
include __DIR__ . '/../template/header.php';
include __DIR__ . '/../template/sidebar.php';
?>

<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Transaksi Baru</h1>

    <form action="proses_transaksi.php" method="POST" id="form-transaksi">
        <div class="row">
            <!-- Kolom Kiri: Keranjang dan Item -->
            <div class="col-lg-7">
                <div class="card shadow mb-4">
                    <div class="card-header">
                        <h6 class="m-0 font-weight-bold text-primary">Cari & Tambah Item</h6>
                    </div>
                    <div class="card-body">
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-search"></i></span>
                            <input type="text" id="cari-item" class="form-control" placeholder="Ketik Kode atau Nama Item...">
                        </div>
                    </div>
                </div>

                <div class="card shadow mb-4">
                    <div class="card-header">
                        <h6 class="m-0 font-weight-bold text-primary">Keranjang Belanja</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Item</th>
                                        <th>Harga</th>
                                        <th>Qty</th>
                                        <th>Subtotal</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="keranjang-body">
                                    <tr><td colspan="5" class="text-center">Keranjang masih kosong</td></tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Kolom Kanan: Detail Pembayaran -->
            <div class="col-lg-5">
                <div class="card shadow mb-4">
                    <div class="card-header">
                        <h6 class="m-0 font-weight-bold text-primary">Detail Pembayaran</h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="id_pelanggan" class="form-label">Pelanggan</label>
                            <select name="id_pelanggan" id="id_pelanggan" class="form-select">
                                <option value="">-- Pelanggan Umum --</option>
                                <?php
                                $query_pelanggan = mysqli_query($koneksi, "SELECT * FROM pelanggan");
                                while ($p = mysqli_fetch_assoc($query_pelanggan)) {
                                    echo "<option value='{$p['id_pelanggan']}'>{$p['nama_pelanggan']}</option>";
                                }
                                ?>
                            </select>
                        </div>

                        <hr>
                        
                        <h4>Total Belanja: <span id="total-belanja-text" class="float-end">Rp 0</span></h4>

                        <div class="input-group my-3">
                            <span class="input-group-text">Pajak (%)</span>
                            <input type="number" class="form-control" id="pajak-persen" name="pajak_persen" value="0" min="0" step="0.1">
                        </div>

                        <hr>

                        <h3 class="fw-bold">Total Final: <span id="total-final-text" class="float-end text-success">Rp 0</span></h3>
                        <input type="hidden" name="total_final" id="total-final-input" value="0">
                        <input type="hidden" name="keranjang" id="keranjang-json">


                        <div class="mt-4">
                            <label class="form-label">Metode Pembayaran</label>
                            <div class="d-flex">
                                <div class="form-check me-3">
                                    <input class="form-check-input" type="radio" name="metode_pembayaran" id="cash" value="CASH" checked>
                                    <label class="form-check-label" for="cash">Cash</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="metode_pembayaran" id="qris" value="QRIS">
                                    <label class="form-check-label" for="qris">QRIS</label>
                                </div>
                            </div>
                        </div>

                        <div class="input-group my-3">
                            <span class="input-group-text">Tunai</span>
                            <input type="text" class="form-control" id="tunai" name="tunai" required>
                        </div>
                        
                        <h4>Kembalian: <span id="kembalian-text" class="float-end">Rp 0</span></h4>
                        
                        <div class="d-grid gap-2 mt-4">
                            <button type="submit" name="simpan_transaksi" class="btn btn-primary btn-lg">
                                <i class="bi bi-save"></i> Simpan Transaksi
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<?php
include __DIR__ . '/../template/footer.php';
?>