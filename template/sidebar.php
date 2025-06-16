<?php
// Mendapatkan nama file dari halaman saat ini untuk menandai link aktif
$current_page = basename($_SERVER['SCRIPT_NAME']);

// Fungsi untuk mengecek beberapa halaman terkait
function is_active($pages, $current) {
    if (is_array($pages)) {
        return in_array($current, $pages) ? 'active' : '';
    }
    return ($current == $pages) ? 'active' : '';
}

// Kelompokkan halaman agar lebih mudah diatur
$transaksi_pages = ['transaksi_baru.php', 'daftar_transaksi.php'];
$master_data_pages = ['item.php', 'pelanggan.php', 'satuan.php', 'stok.php'];
$laporan_pages = ['laporan_penjualan.php', 'laporan_stok_menipis.php'];
$admin_pages = ['users.php'];

?>
<nav class="sidebar">
    <div class="sidebar-brand">
        ACA POS
    </div>
    <div class="nav flex-column">
        <!-- Dashboard -->
        <a class="nav-link <?php echo is_active('dashboard.php', $current_page); ?>" href="/aca_pos/dashboard.php">
            <i class="bi bi-grid-1x2-fill"></i><span>Dashboard</span>
        </a>
        <a class="nav-link <?php echo is_active($transaksi_pages, $current_page); ?>" href="/aca_pos/transaksi/transaksi_baru.php">
            <i class="bi bi-cart-plus-fill"></i><span>Kasir</span>
        </a>
        <a class="nav-link <?php echo is_active('daftar_transaksi.php', $current_page); ?>" href="/aca_pos/transaksi/daftar_transaksi.php">
            <i class="bi bi-receipt"></i><span>Riwayat Transaksi</span>
        </a>
        
        <!-- Master Data -->
        <div class="nav-link-title">Master Data</div>
        <a class="nav-link <?php echo is_active('item.php', $current_page); ?>" href="/aca_pos/master_data/item.php">
            <i class="bi bi-box-seam-fill"></i><span>Daftar Item</span>
        </a>
        <a class="nav-link <?php echo is_active('pelanggan.php', $current_page); ?>" href="/aca_pos/master_data/pelanggan.php">
            <i class="bi bi-people-fill"></i><span>Pelanggan</span>
        </a>
        <a class="nav-link <?php echo is_active('satuan.php', $current_page); ?>" href="/aca_pos/master_data/satuan.php">
            <i class="bi bi-rulers"></i><span>Satuan</span>
        </a>
         <a class="nav-link <?php echo is_active('stok.php', $current_page); ?>" href="/aca_pos/master_data/stok.php">
            <i class="bi bi-plus-square-fill"></i><span>Tambah Stok</span>
        </a>
        
        <?php if ($_SESSION['role'] == 'administrator'): ?>
        <!-- Laporan -->
        <div class="nav-link-title">Laporan</div>
        <a class="nav-link <?php echo is_active($laporan_pages, $current_page); ?>" href="/aca_pos/laporan/laporan_penjualan.php">
            <i class="bi bi-file-earmark-bar-graph-fill"></i><span>Laporan Penjualan</span>
        </a>
        <a class="nav-link <?php echo is_active('laporan_stok_menipis.php', $current_page); ?>" href="/aca_pos/laporan/laporan_stok_menipis.php">
            <i class="bi bi-exclamation-triangle-fill"></i><span>Stok Menipis</span>
        </a>

        <!-- Administrasi -->
        <div class="nav-link-title">Administrasi</div>
        <a class="nav-link <?php echo is_active('users.php', $current_page); ?>" href="/aca_pos/admin/users.php">
            <i class="bi bi-person-fill-gear"></i><span>Manajemen User</span>
        </a>
        <?php endif; ?>
    </div>

    <!-- Bagian Logout didorong ke bawah -->
    <div class="logout-section">
        <hr>
        <a class="nav-link logout" href="/aca_pos/logout.php" onclick="return confirm('Apakah Anda yakin ingin logout?')">
            <i class="bi bi-box-arrow-left"></i><span>Logout</span>
        </a>
    </div>
</nav>

<main class="main-content">
    <!-- Header Halaman Dinamis -->
    <header class="page-header">
        <h1 class="page-title"><?php echo isset($title) ? $title : 'Dashboard'; ?></h1>
        <div class="user-profile">
            <span class="user-greeting">Halo, <?php echo htmlspecialchars($nama_user); ?>!</span>
            <i class="bi bi-person-circle user-avatar"></i>
        </div>
    </header>
    
    <!-- Konten halaman akan dimulai di sini -->
    <div class="page-body">