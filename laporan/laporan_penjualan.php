<?php
$title = "Laporan Penjualan";
include __DIR__ . '/../template/header.php';

// Proteksi Halaman: Hanya untuk Administrator
if ($_SESSION['role'] != 'administrator') {
    // Arahkan ke dashboard jika bukan admin
    echo "<script>alert('Anda tidak memiliki akses ke halaman ini!'); window.location.href='/aca_pos/dashboard.php';</script>";
    exit;
}

include __DIR__ . '/../template/sidebar.php';

// Inisialisasi variabel untuk filter dan judul
$where_clause = "";
$sub_judul = "Semua Transaksi";

// Proses filter jika form disubmit
if (isset($_GET['filter'])) {
    $jenis_laporan = $_GET['jenis_laporan'];

    switch ($jenis_laporan) {
        case 'harian':
            $tanggal = mysqli_real_escape_string($koneksi, $_GET['tanggal']);
            if (!empty($tanggal)) {
                $where_clause = "WHERE DATE(t.tanggal_transaksi) = '$tanggal'";
                $sub_judul = "Laporan Harian pada Tanggal " . date('d F Y', strtotime($tanggal));
            }
            break;

        case 'mingguan':
            $tanggal_minggu = mysqli_real_escape_string($koneksi, $_GET['tanggal_minggu']);
            if (!empty($tanggal_minggu)) {
                $tahun = date('Y', strtotime($tanggal_minggu));
                $minggu_ke = date('W', strtotime($tanggal_minggu));
                $tgl_awal = date('Y-m-d', strtotime($tahun."W".$minggu_ke.'1')); // Senin
                $tgl_akhir = date('Y-m-d', strtotime($tahun."W".$minggu_ke.'7')); // Minggu
                $where_clause = "WHERE DATE(t.tanggal_transaksi) BETWEEN '$tgl_awal' AND '$tgl_akhir'";
                $sub_judul = "Laporan Mingguan dari " . date('d M Y', strtotime($tgl_awal)) . " s/d " . date('d M Y', strtotime($tgl_akhir));
            }
            break;
            
        case 'bulanan':
            $bulan = mysqli_real_escape_string($koneksi, $_GET['bulan']);
            if (!empty($bulan)) {
                $where_clause = "WHERE DATE_FORMAT(t.tanggal_transaksi, '%Y-%m') = '$bulan'";
                $sub_judul = "Laporan Bulanan untuk " . date('F Y', strtotime($bulan . '-01'));
            }
            break;

        case 'tahunan':
            $tahun = mysqli_real_escape_string($koneksi, $_GET['tahun']);
            if(!empty($tahun)) {
                $where_clause = "WHERE YEAR(t.tanggal_transaksi) = '$tahun'";
                $sub_judul = "Laporan Tahunan untuk Tahun " . $tahun;
            }
            break;
    }
}

// Query untuk mengambil data total
$query_total = "SELECT COUNT(*) as total_transaksi, SUM(total_final) as total_penjualan FROM transaksi t $where_clause";
$result_total = mysqli_query($koneksi, $query_total);
$data_total = mysqli_fetch_assoc($result_total);
$total_transaksi = $data_total['total_transaksi'] ?: 0;
$total_penjualan = $data_total['total_penjualan'] ?: 0;

?>

<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Laporan Penjualan</h1>

    <!-- Form Filter -->
    <div class="card shadow mb-4">
        <div class="card-header">
            <h6 class="m-0 font-weight-bold text-primary">Filter Laporan</h6>
        </div>
        <div class="card-body">
            <form action="" method="GET">
                <div class="row align-items-end">
                    <div class="col-md-3">
                        <label for="jenis_laporan" class="form-label">Jenis Laporan</label>
                        <select name="jenis_laporan" id="jenis_laporan" class="form-select">
                            <option value="semua" <?= (isset($_GET['jenis_laporan']) && $_GET['jenis_laporan'] == 'semua') ? 'selected' : ''; ?>>Semua</option>
                            <option value="harian" <?= (isset($_GET['jenis_laporan']) && $_GET['jenis_laporan'] == 'harian') ? 'selected' : ''; ?>>Harian</option>
                            <option value="mingguan" <?= (isset($_GET['jenis_laporan']) && $_GET['jenis_laporan'] == 'mingguan') ? 'selected' : ''; ?>>Mingguan</option>
                            <option value="bulanan" <?= (isset($_GET['jenis_laporan']) && $_GET['jenis_laporan'] == 'bulanan') ? 'selected' : ''; ?>>Bulanan</option>
                            <option value="tahunan" <?= (isset($_GET['jenis_laporan']) && $_GET['jenis_laporan'] == 'tahunan') ? 'selected' : ''; ?>>Tahunan</option>
                        </select>
                    </div>

                    <!-- Input Fields for Filters -->
                    <div class="col-md-4" id="input-harian">
                        <label for="tanggal" class="form-label">Pilih Tanggal</label>
                        <input type="date" name="tanggal" class="form-control" value="<?= $_GET['tanggal'] ?? date('Y-m-d') ?>">
                    </div>
                     <div class="col-md-4" id="input-mingguan">
                        <label for="tanggal_minggu" class="form-label">Pilih Tanggal dalam Minggu</label>
                        <input type="date" name="tanggal_minggu" class="form-control" value="<?= $_GET['tanggal_minggu'] ?? date('Y-m-d') ?>">
                    </div>
                    <div class="col-md-4" id="input-bulanan">
                        <label for="bulan" class="form-label">Pilih Bulan</label>
                        <input type="month" name="bulan" class="form-control" value="<?= $_GET['bulan'] ?? date('Y-m') ?>">
                    </div>
                    <div class="col-md-4" id="input-tahunan">
                        <label for="tahun" class="form-label">Pilih Tahun</label>
                        <input type="number" name="tahun" class="form-control" placeholder="Contoh: 2024" value="<?= $_GET['tahun'] ?? date('Y') ?>">
                    </div>
                    
                    <div class="col-md-2">
                        <button type="submit" name="filter" value="true" class="btn btn-primary">Terapkan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Ringkasan Laporan -->
     <div class="row">
        <div class="col-xl-6 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Jumlah Transaksi</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $total_transaksi ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-receipt fs-2 text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
         <div class="col-xl-6 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Total Penjualan</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= format_rupiah($total_penjualan) ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-cash-coin fs-2 text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Tabel Laporan -->
    <div class="card shadow mb-4">
        <div class="card-header">
             <h6 class="m-0 font-weight-bold text-primary"><?= $sub_judul ?></h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Invoice</th>
                            <th>Tanggal</th>
                            <th>Kasir</th>
                            <th>Pelanggan</th>
                            <th class="text-end">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $query = "SELECT t.*, u.nama_lengkap AS nama_kasir, p.nama_pelanggan 
                                  FROM transaksi t
                                  JOIN users u ON t.id_user = u.id_user
                                  LEFT JOIN pelanggan p ON t.id_pelanggan = p.id_pelanggan
                                  $where_clause
                                  ORDER BY t.tanggal_transaksi DESC";
                        
                        $result = mysqli_query($koneksi, $query);
                        $no = 1;

                        if (mysqli_num_rows($result) > 0) {
                            while ($data = mysqli_fetch_assoc($result)) {
                        ?>
                            <tr>
                                <td><?= $no++; ?></td>
                                <td><a href="/aca_pos/transaksi/detail_transaksi.php?id=<?= $data['id_transaksi'] ?>"><?= $data['kode_invoice'] ?></a></td>
                                <td><?= date('d M Y H:i', strtotime($data['tanggal_transaksi'])) ?></td>
                                <td><?= htmlspecialchars($data['nama_kasir']) ?></td>
                                <td><?= htmlspecialchars($data['nama_pelanggan'] ?: 'Umum') ?></td>
                                <td class="text-end"><?= format_rupiah($data['total_final']) ?></td>
                            </tr>
                        <?php
                            }
                        } else {
                            echo "<tr><td colspan='6' class='text-center'>Tidak ada data transaksi untuk periode yang dipilih.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const jenisLaporan = document.getElementById('jenis_laporan');
    const inputHarian = document.getElementById('input-harian');
    const inputMingguan = document.getElementById('input-mingguan');
    const inputBulanan = document.getElementById('input-bulanan');
    const inputTahunan = document.getElementById('input-tahunan');

    function toggleInputs() {
        // Hide all first
        inputHarian.style.display = 'none';
        inputMingguan.style.display = 'none';
        inputBulanan.style.display = 'none';
        inputTahunan.style.display = 'none';

        // Show the selected one
        const selectedValue = jenisLaporan.value;
        if (selectedValue === 'harian') {
            inputHarian.style.display = 'block';
        } else if (selectedValue === 'mingguan') {
            inputMingguan.style.display = 'block';
        } else if (selectedValue === 'bulanan') {
            inputBulanan.style.display = 'block';
        } else if (selectedValue === 'tahunan') {
            inputTahunan.style.display = 'block';
        }
    }

    // Call on change
    jenisLaporan.addEventListener('change', toggleInputs);

    // Call on page load to set initial state
    toggleInputs();
});
</script>


<?php
include __DIR__ . '/../template/footer.php';
?>