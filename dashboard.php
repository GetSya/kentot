<?php
$title = "Dashboard";
include __DIR__ . '/template/header.php';
include __DIR__ . '/template/sidebar.php';

// Mengambil data untuk dashboard
// Jumlah Pelanggan
$pelanggan = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM pelanggan"))['total'];

// Jumlah Item
$item = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM item"))['total'];

// Jumlah Transaksi Hari Ini
$today = date('Y-m-d');
$transaksi_hari_ini = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM transaksi WHERE DATE(tanggal_transaksi) = '$today'"))['total'];

// Total Penjualan Hari Ini
$penjualan_hari_ini = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT SUM(total_final) AS total FROM transaksi WHERE DATE(tanggal_transaksi) = '$today'"))['total'];
$penjualan_hari_ini = $penjualan_hari_ini ?: 0; // jika null, jadikan 0

?>

<div class="container-fluid">
    <h1 class="mb-4">Dashboard</h1>

    <div class="row">
        <!-- Card Transaksi Hari Ini -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Transaksi (Hari Ini)</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $transaksi_hari_ini ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-calendar-check fs-2 text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card Penjualan Hari Ini -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Penjualan (Hari Ini)</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= format_rupiah($penjualan_hari_ini) ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-cash-coin fs-2 text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Card Jumlah Pelanggan -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Jumlah Pelanggan</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $pelanggan ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-people-fill fs-2 text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Card Jumlah Item -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Jumlah Item</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $item ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-box-seam-fill fs-2 text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow mt-4">
        <div class="card-header">
            <h5 class="card-title">Selamat Datang, <?= $_SESSION['nama_lengkap'] ?>!</h5>
        </div>
        <div class="card-body">
            <p>Anda login sebagai <strong><?= ucfirst($_SESSION['role']) ?></strong>.</p>
            <p>Gunakan menu di sebelah kiri untuk menavigasi aplikasi.</p>
        </div>
    </div>
</div>

<?php
include __DIR__ . '/template/footer.php';
?>